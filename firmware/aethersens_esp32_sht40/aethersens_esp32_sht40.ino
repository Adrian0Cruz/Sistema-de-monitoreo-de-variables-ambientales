/*
 ================================================================================
  AETHERSENS ENTERPRISE - FIRMWARE IoT OFICIAL PARA ESP32
  Monitoreo Térmico, Humedad y Humo para Sala Técnica - Intalnet (Montería, CO)
 ================================================================================
  Hardware Soportado:
    - Microcontrolador: ESP32 DevKit USB-C (30 o 38 pines)
    - Sensor Temp/Hum: Sensirion SHT40 (I2C en GPIO 21 SDA / GPIO 22 SCL)
    - Sensor Gas/Humo: MQ-2 Salida Analógica (GPIO 34 ADC1_CH6)
    - Semáforo LED:
        * LED Verde:    GPIO 25 (Estado Normal)
        * LED Amarillo: GPIO 32 (Advertencia)
        * LED Rojo:     GPIO 33 (Crítico / Humo)
    - Buzzer / Alarma Sonora: GPIO 26 (Driver con transistor 2N2222)
    - Pulsador Industrial NO: GPIO 27 (Silenciamiento manual con pull-up interno)

  Librerías Utilizadas en Arduino IDE:
    - WiFi (incluida nativamente en el core ESP32)
    - PubSubClient de Nick O'Leary (gestor de bibliotecas)
    - ArduinoJson de Benoit Blanchon (versión 7.x instalada)
    - Preferences (incluida nativamente en el core ESP32 para Flash NVS)
    - Wire (incluida nativamente en el core ESP32 para comunicación I2C)

  Universidad de Córdoba - Montería, Colombia
  Desarrolladores: Cristian González, Jesús Hernández, Julio Nova, Jorge Oviedo, Dariel Ortega
 ================================================================================
*/

#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>
#include <Preferences.h>
#include <Wire.h>

// ==============================================================================
// 1. CONFIGURACIÓN DE RED WI-FI Y BROKER MQTT
// ==============================================================================
// Ingrese el SSID y la contraseña de la red Wi-Fi de la sala técnica:
const char* WIFI_SSID     = "INTALNET_SALA_TECNICA"; // Reemplace con su red Wi-Fi
const char* WIFI_PASSWORD = "PasswordSegura123";      // Reemplace con su contraseña

// Configuración del Broker MQTT:
// Para pruebas públicas en internet: "broker.hivemq.com" (puerto 1883)
// Para el contenedor local Mosquitto en producción: IP local del servidor Docker (puerto 1883)
const char* MQTT_BROKER   = "broker.hivemq.com";
const int   MQTT_PORT     = 1883;

// Identificadores de este nodo físico:
const char* DISPOSITIVO_ID = "ESP32_SALA01";
const char* SALA_ID        = "sala01";

// Tópicos MQTT oficiales del sistema AetherSens:
const char* TOPIC_TELEMETRIA = "intalnet/sala01/telemetria";
const char* TOPIC_ESTADO     = "intalnet/sala01/estado";
const char* TOPIC_ALERTA     = "intalnet/sala01/alerta";
const char* TOPIC_CONFIG     = "intalnet/sala01/config";

// ==============================================================================
// 2. ASIGNACIÓN DE PINES GPIO
// ==============================================================================
#define PIN_SDA           21
#define PIN_SCL           22
#define PIN_MQ2_ADC       34
#define PIN_LED_VERDE     25
#define PIN_LED_AMARILLO  32
#define PIN_LED_ROJO      33
#define PIN_BUZZER        26
#define PIN_PULSADOR_NO   27

// Dirección I2C del sensor SHT40:
#define SHT40_I2C_ADDR    0x44
#define SHT40_CMD_MEASURE 0xFD // Medición en alta precisión

// ==============================================================================
// 3. VARIABLES GLOBALES Y UMBRALES DINÁMICOS
// ==============================================================================
// Umbrales calibrables dinámicamente desde la interfaz web de AetherSens:
float tempUmbralAdvertencia = 28.0; // °C
float tempUmbralCritico     = 35.0; // °C -> Activa LED Rojo y Buzzer
int   mq2UmbralHumo         = 1500; // Escala ADC (0 - 4095)

// Estados operativos:
enum EstadoOperativo {
  ESTADO_NORMAL,
  ESTADO_ADVERTENCIA,
  ESTADO_CRITICO,
  ESTADO_HUMO_DETECTADO
};

EstadoOperativo estadoActual   = ESTADO_NORMAL;
EstadoOperativo estadoAnterior = ESTADO_NORMAL;

bool  buzzerSilenciado      = false;
unsigned long ultimoEnvioMs = 0;
const unsigned long INTERVALO_ENVIO_MS = 5000; // Cadencia: cada 5 segundos

// Objetos del sistema:
WiFiClient espClient;
PubSubClient mqttClient(espClient);
Preferences preferences;

// ==============================================================================
// 4. FUNCIONES DE LECTURA DE SENSORES
// ==============================================================================

/**
 * Lee temperatura y humedad relativa desde el sensor SHT40 vía I2C nativo.
 */
bool leerSHT40(float &temperatura, float &humedad) {
  Wire.beginTransmission(SHT40_I2C_ADDR);
  Wire.write(SHT40_CMD_MEASURE);
  if (Wire.endTransmission() != 0) {
    return false; // Error de comunicación I2C
  }

  delay(10); // Tiempo de conversión del SHT40 en alta precisión (~8.2 ms)

  if (Wire.requestFrom(SHT40_I2C_ADDR, 6) != 6) {
    return false;
  }

  uint16_t t_raw = (Wire.read() << 8) | Wire.read();
  uint8_t  t_crc = Wire.read();
  uint16_t h_raw = (Wire.read() << 8) | Wire.read();
  uint8_t  h_crc = Wire.read();

  // Fórmulas oficiales del fabricante Sensirion para SHT40:
  temperatura = -45.0 + 175.0 * ((float)t_raw / 65535.0);
  humedad     = -6.0 + 125.0 * ((float)h_raw / 65535.0);

  // Limitar humedad al rango físico 0 - 100%
  if (humedad < 0.0)   humedad = 0.0;
  if (humedad > 100.0) humedad = 100.0;

  return true;
}

/**
 * Lee el nivel de gas y humo del sensor analógico MQ-2 con promedio móvil.
 */
int leerMQ2() {
  const int MUESTRAS = 8;
  long suma = 0;
  for (int i = 0; i < MUESTRAS; i++) {
    suma += analogRead(PIN_MQ2_ADC);
    delayMicroseconds(250);
  }
  return (int)(suma / MUESTRAS);
}

// ==============================================================================
// 5. GESTIÓN DE MEMORIA FLASH NVS (PREFERENCES)
// ==============================================================================

/**
 * Carga los umbrales guardados previamente desde la memoria Flash permanente.
 */
void cargarUmbralesDesdeNVS() {
  preferences.begin("aethersens", true); // Modo sólo lectura
  tempUmbralAdvertencia = preferences.getFloat("t_adv", 28.0);
  tempUmbralCritico     = preferences.getFloat("t_crit", 35.0);
  mq2UmbralHumo         = preferences.getInt("gas_h", 1500);
  preferences.end();

  Serial.println("[NVS] Umbrales cargados de memoria Flash:");
  Serial.printf("  - Temp Advertencia: %.1f °C\n", tempUmbralAdvertencia);
  Serial.printf("  - Temp Crítica:     %.1f °C\n", tempUmbralCritico);
  Serial.printf("  - Humo MQ-2:        %d ADC\n", mq2UmbralHumo);
}

/**
 * Guarda los nuevos umbrales enviados por la página web en la memoria Flash.
 */
void guardarUmbralesEnNVS(float tAdv, float tCrit, int gasHum) {
  preferences.begin("aethersens", false); // Modo lectura/escritura
  preferences.putFloat("t_adv", tAdv);
  preferences.putFloat("t_crit", tCrit);
  preferences.putInt("gas_h", gasHum);
  preferences.end();

  tempUmbralAdvertencia = tAdv;
  tempUmbralCritico     = tCrit;
  mq2UmbralHumo         = gasHum;

  Serial.println("[NVS] ¡Nuevos umbrales guardados permanentemente en Flash!");
}

// ==============================================================================
// 6. GESTIÓN DE ACTUADORES FÍSICOS (SEMÁFORO LED + BUZZER)
// ==============================================================================

void actualizarActuadores(EstadoOperativo estado) {
  switch (estado) {
    case ESTADO_NORMAL:
      digitalWrite(PIN_LED_VERDE, HIGH);
      digitalWrite(PIN_LED_AMARILLO, LOW);
      digitalWrite(PIN_LED_ROJO, LOW);
      digitalWrite(PIN_BUZZER, LOW);
      buzzerSilenciado = false; // Rearmar automáticamente el buzzer
      break;

    case ESTADO_ADVERTENCIA:
      digitalWrite(PIN_LED_VERDE, LOW);
      digitalWrite(PIN_LED_AMARILLO, HIGH);
      digitalWrite(PIN_LED_ROJO, LOW);
      digitalWrite(PIN_BUZZER, LOW);
      break;

    case ESTADO_CRITICO:
    case ESTADO_HUMO_DETECTADO:
      digitalWrite(PIN_LED_VERDE, LOW);
      digitalWrite(PIN_LED_AMARILLO, LOW);
      digitalWrite(PIN_LED_ROJO, HIGH); // El LED rojo de advertencia NUNCA se apaga en alarma

      // El buzzer solo suena si el operador no lo ha silenciado manualmente con el pulsador
      if (!buzzerSilenciado) {
        digitalWrite(PIN_BUZZER, HIGH);
      } else {
        digitalWrite(PIN_BUZZER, LOW);
      }
      break;
  }
}

/**
 * Comprueba el pulsador industrial NO (Normalmente Abierto) para silenciar el buzzer.
 */
void verificarPulsadorSilenciamiento() {
  // Con INPUT_PULLUP, al presionar el pulsador se lee LOW (GND)
  if (digitalRead(PIN_PULSADOR_NO) == LOW) {
    if ((estadoActual == ESTADO_CRITICO || estadoActual == ESTADO_HUMO_DETECTADO) && !buzzerSilenciado) {
      buzzerSilenciado = true;
      digitalWrite(PIN_BUZZER, LOW); // Silenciar bocina inmediatamente
      Serial.println("[ALERTA] Pulsador presionado: Buzzer silenciado manualmente por operador.");

      // Publicar evento de silenciamiento en el broker MQTT con ArduinoJson v7
      JsonDocument doc;
      doc["evento"]      = "SILENCIADO_MANUAL";
      doc["dispositivo"] = DISPOSITIVO_ID;
      doc["sala_id"]     = SALA_ID;
      doc["mensaje"]     = "Alarma sonora silenciada manualmente en sitio por el operador";
      doc["timestamp_s"] = millis() / 1000;

      char buffer[256];
      serializeJson(doc, buffer);
      mqttClient.publish(TOPIC_ALERTA, buffer, true);

      delay(300); // Debounce de botón
    }
  }
}

// ==============================================================================
// 7. CALLBACK MQTT: RECEPCIÓN DE LÍMITES Y CALIBRACIÓN REMOTA
// ==============================================================================

void mqttCallback(char* topic, byte* payload, unsigned int length) {
  Serial.printf("[MQTT] Mensaje recibido en [%s]: ", topic);

  // Convertir payload a string terminada en null
  char mensaje[length + 1];
  memcpy(mensaje, payload, length);
  mensaje[length] = '\0';
  Serial.println(mensaje);

  // Si el mensaje es una calibración de umbrales desde la página web de AetherSens
  if (String(topic) == TOPIC_CONFIG) {
    JsonDocument doc;
    DeserializationError error = deserializeJson(doc, mensaje);

    if (!error) {
      // ── Comando remoto de silenciamiento desde la web ──
      if (!doc["silenciar_buzzer"].isNull() && doc["silenciar_buzzer"].as<bool>() == true) {
        if ((estadoActual == ESTADO_CRITICO || estadoActual == ESTADO_HUMO_DETECTADO) && !buzzerSilenciado) {
          buzzerSilenciado = true;
          digitalWrite(PIN_BUZZER, LOW);
          Serial.println("[ALERTA] Buzzer silenciado remotamente desde la interfaz web.");

          JsonDocument silDoc;
          silDoc["evento"]      = "SILENCIADO_REMOTO";
          silDoc["dispositivo"] = DISPOSITIVO_ID;
          silDoc["sala_id"]     = SALA_ID;
          silDoc["mensaje"]     = "Alarma sonora silenciada remotamente desde el panel web AetherSens";
          silDoc["timestamp_s"] = millis() / 1000;

          char silBuf[256];
          serializeJson(silDoc, silBuf);
          mqttClient.publish(TOPIC_ALERTA, silBuf, false);
        }
        return; // Comando de silenciamiento procesado, no es calibración
      }

      float nuevoAdv  = (!doc["temp_adv"].isNull())  ? doc["temp_adv"].as<float>()  : tempUmbralAdvertencia;
      float nuevoCrit = (!doc["temp_crit"].isNull()) ? doc["temp_crit"].as<float>() : tempUmbralCritico;
      int   nuevoGas  = (!doc["gas_humo"].isNull())  ? doc["gas_humo"].as<int>()    : mq2UmbralHumo;

      guardarUmbralesEnNVS(nuevoAdv, nuevoCrit, nuevoGas);

      // Confirmar actualización por MQTT al panel web
      JsonDocument ack;
      ack["evento"]      = "CALIBRACION_APLICADA";
      ack["dispositivo"] = DISPOSITIVO_ID;
      ack["temp_adv"]    = tempUmbralAdvertencia;
      ack["temp_crit"]   = tempUmbralCritico;
      ack["gas_humo"]    = mq2UmbralHumo;

      char ackBuf[256];
      serializeJson(ack, ackBuf);
      mqttClient.publish(TOPIC_ALERTA, ackBuf);
    } else {
      Serial.println("[MQTT Error] Fallo al parsear JSON de configuración.");
    }
  }
}

// ==============================================================================
// 8. CONEXIÓN WI-FI Y RECONEXIÓN MQTT CON MENSAJE DE TESTAMENTO (LWT)
// ==============================================================================

void setupWiFi() {
  delay(10);
  Serial.println();
  Serial.printf("[WiFi] Conectando a %s", WIFI_SSID);

  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int intentos = 0;
  while (WiFi.status() != WL_CONNECTED && intentos < 30) {
    delay(500);
    Serial.print(".");
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WiFi] ¡Conectado con éxito!");
    Serial.printf("[WiFi] Dirección IP: %s\n", WiFi.localIP().toString().c_str());
    Serial.printf("[WiFi] Intensidad RSSI: %d dBm\n", WiFi.RSSI());
  } else {
    Serial.println("\n[WiFi Advertencia] No se pudo conectar a la red. El nodo operará en modo autónomo local.");
  }
}

void reconectarMQTT() {
  while (!mqttClient.connected() && WiFi.status() == WL_CONNECTED) {
    Serial.print("[MQTT] Conectando al broker...");

    String clientId = "AetherSens-ESP32-" + String(random(0xffff), HEX);

    // Mensaje de Última Voluntad y Testamento (LWT) con retención:
    // Si el ESP32 pierde alimentación o conexión abruptamente, el broker lo notificará de inmediato
    const char* lwtPayload = "{\"dispositivo\":\"ESP32_SALA01\",\"estado\":\"OFFLINE\"}";

    if (mqttClient.connect(clientId.c_str(), TOPIC_ESTADO, 1, true, lwtPayload)) {
      Serial.println(" ¡Conectado!");

      // 1. Publicar mensaje de nacimiento (Birth message) con ArduinoJson v7
      JsonDocument birthDoc;
      birthDoc["dispositivo"] = DISPOSITIVO_ID;
      birthDoc["estado"]      = "ONLINE";
      birthDoc["ip"]          = WiFi.localIP().toString();
      birthDoc["rssi"]        = WiFi.RSSI();

      char birthBuffer[256];
      serializeJson(birthDoc, birthBuffer);
      mqttClient.publish(TOPIC_ESTADO, birthBuffer, true);

      // 2. Suscribirse al tópico de configuración para recibir los límites de la página web
      mqttClient.subscribe(TOPIC_CONFIG, 1);
      Serial.printf("[MQTT] Suscrito con éxito al tópico: %s\n", TOPIC_CONFIG);

    } else {
      Serial.printf(" Falló (rc=%d). Reintentando en 4 segundos...\n", mqttClient.state());
      delay(4000);
    }
  }
}

// ==============================================================================
// 9. SETUP (INICIALIZACIÓN DEL SISTEMA)
// ==============================================================================

void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("\n========================================================");
  Serial.println("  AETHERSENS ENTERPRISE - INICIALIZANDO FIRMWARE ESP32  ");
  Serial.println("  Monitoreo Térmico y Humedad - Intalnet (Montería, CO) ");
  Serial.println("========================================================");

  // Configuración de pines GPIO:
  pinMode(PIN_LED_VERDE, OUTPUT);
  pinMode(PIN_LED_AMARILLO, OUTPUT);
  pinMode(PIN_LED_ROJO, OUTPUT);
  pinMode(PIN_BUZZER, OUTPUT);
  pinMode(PIN_PULSADOR_NO, INPUT_PULLUP);

  // Inicializar actuadores en estado seguro (apagados):
  digitalWrite(PIN_LED_VERDE, LOW);
  digitalWrite(PIN_LED_AMARILLO, LOW);
  digitalWrite(PIN_LED_ROJO, LOW);
  digitalWrite(PIN_BUZZER, LOW);

  // Configurar bus I2C para sensor SHT40:
  Wire.begin(PIN_SDA, PIN_SCL, 400000); // 400 kHz Fast-Mode I2C

  // Cargar umbrales desde memoria Flash permanente:
  cargarUmbralesDesdeNVS();

  // Iniciar conexión de red:
  setupWiFi();

  // Configurar cliente MQTT:
  mqttClient.setServer(MQTT_BROKER, MQTT_PORT);
  mqttClient.setCallback(mqttCallback);
  mqttClient.setBufferSize(512); // Buffer amplio para JSON

  Serial.println("[Sistema] Inicialización completada. Iniciando bucle principal.");
}

// ==============================================================================
// 10. LOOP (CICLO PRINCIPAL NO BLOQUEANTE)
// ==============================================================================

void loop() {
  // 1. Mantener enlace Wi-Fi y MQTT si hay conectividad:
  if (WiFi.status() == WL_CONNECTED) {
    if (!mqttClient.connected()) {
      reconectarMQTT();
    }
    mqttClient.loop();
  }

  // 2. Verificar pulsador industrial de silenciamiento:
  verificarPulsadorSilenciamiento();

  // 3. Cadencia de lectura y telemetría (cada 5 segundos):
  unsigned long ahora = millis();
  if (ahora - ultimoEnvioMs >= INTERVALO_ENVIO_MS) {
    ultimoEnvioMs = ahora;

    float temperatura = 0.0;
    float humedad     = 0.0;
    bool okSHT40 = leerSHT40(temperatura, humedad);

    if (!okSHT40) {
      Serial.println("[Error I2C] No se pudo leer el sensor SHT40. Verifique cableado SDA/SCL.");
      temperatura = 25.0; // Valor seguro por defecto si falla conexión
      humedad     = 50.0;
    }

    int nivelGas = leerMQ2();

    // 4. Evaluar estado operativo con histéresis para evitar oscilaciones rápidas (flicker):
    const int   HISTERESIS_GAS  = 120; // Margen de despeje para sensor MQ-2 (ADC)
    const float HISTERESIS_TEMP = 0.5; // Margen de despeje para temperatura (°C)

    if (estadoActual == ESTADO_HUMO_DETECTADO) {
      // Para salir de estado de humo, el gas debe descender por debajo del umbral menos el margen de histéresis
      if (nivelGas < (mq2UmbralHumo - HISTERESIS_GAS)) {
        if (temperatura >= tempUmbralCritico) {
          estadoActual = ESTADO_CRITICO;
        } else if (temperatura >= tempUmbralAdvertencia) {
          estadoActual = ESTADO_ADVERTENCIA;
        } else {
          estadoActual = ESTADO_NORMAL;
        }
      }
    } else if (nivelGas >= mq2UmbralHumo) {
      estadoActual = ESTADO_HUMO_DETECTADO;
    } else if (estadoActual == ESTADO_CRITICO) {
      if (temperatura < (tempUmbralCritico - HISTERESIS_TEMP)) {
        estadoActual = (temperatura >= tempUmbralAdvertencia) ? ESTADO_ADVERTENCIA : ESTADO_NORMAL;
      }
    } else if (temperatura >= tempUmbralCritico) {
      estadoActual = ESTADO_CRITICO;
    } else if (estadoActual == ESTADO_ADVERTENCIA) {
      if (temperatura < (tempUmbralAdvertencia - HISTERESIS_TEMP)) {
        estadoActual = ESTADO_NORMAL;
      }
    } else if (temperatura >= tempUmbralAdvertencia) {
      estadoActual = ESTADO_ADVERTENCIA;
    } else {
      estadoActual = ESTADO_NORMAL;
    }

    // 5. Actualizar actuadores físicos locales (Semáforo LED y Buzzer):
    actualizarActuadores(estadoActual);

    // 6. Convertir estado a texto para el panel web:
    const char* estadoTexto = "NORMAL";
    if (estadoActual == ESTADO_ADVERTENCIA)      estadoTexto = "ADVERTENCIA";
    if (estadoActual == ESTADO_CRITICO)          estadoTexto = "CRITICO";
    if (estadoActual == ESTADO_HUMO_DETECTADO)  estadoTexto = "HUMO_DETECTADO";

    // 7. Notificar cambio de estado si varió respecto al ciclo anterior:
    if (estadoActual != estadoAnterior) {
      Serial.printf("[Transición] Cambio de estado: %s\n", estadoTexto);

      // Solo publicar en el tópico de alertas si es una condición de ADVERTENCIA o CRÍTICA (sin retención en broker)
      if (mqttClient.connected() && estadoActual != ESTADO_NORMAL) {
        JsonDocument alertDoc;
        alertDoc["evento"]       = "CAMBIO_ESTADO";
        alertDoc["nuevo_estado"] = estadoTexto;
        alertDoc["temperatura"]  = round(temperatura * 10.0) / 10.0;
        alertDoc["humedad"]      = round(humedad * 10.0) / 10.0;
        alertDoc["gas"]          = nivelGas;
        alertDoc["dispositivo"]  = DISPOSITIVO_ID;
        alertDoc["sala_id"]      = SALA_ID;

        char alertBuf[256];
        serializeJson(alertDoc, alertBuf);
        mqttClient.publish(TOPIC_ALERTA, alertBuf, false); // retain = false para no almacenar alertas obsoletas en el broker
      }
      estadoAnterior = estadoActual;
    }

    // 8. Construir payload JSON de telemetría con ArduinoJson v7:
    JsonDocument telemetriaDoc;
    telemetriaDoc["temperatura"]      = round(temperatura * 10.0) / 10.0;
    telemetriaDoc["humedad_relativa"] = round(humedad * 10.0) / 10.0;
    telemetriaDoc["nivel_gas"]        = nivelGas;
    telemetriaDoc["estado"]           = estadoTexto;
    telemetriaDoc["silenciado"]       = buzzerSilenciado;
    telemetriaDoc["uptime_s"]         = ahora / 1000;
    telemetriaDoc["dispositivo"]      = DISPOSITIVO_ID;
    telemetriaDoc["sala_id"]          = SALA_ID;

    char telemetriaBuffer[384];
    serializeJson(telemetriaDoc, telemetriaBuffer);

    // 9. Enviar paquete al broker MQTT:
    if (mqttClient.connected()) {
      mqttClient.publish(TOPIC_TELEMETRIA, telemetriaBuffer);
      Serial.printf("[Telemetría enviada] %s\n", telemetriaBuffer);
    } else {
      Serial.printf("[Modo Autónomo Local] %s\n", telemetriaBuffer);
    }
  }
}
