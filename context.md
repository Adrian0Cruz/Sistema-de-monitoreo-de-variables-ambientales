# Contexto del proyecto — Sistema de monitoreo de sala térmica para Intalnet

> Documento de contexto para utilizar como referencia en nuevas conversaciones, asistentes de IA y durante el desarrollo del proyecto.

## 1. Resumen

Proyecto de aula semestral desarrollado por estudiantes en colaboración con **Intalnet**, empresa con sede en Montería, Córdoba, Colombia.

El proyecto aprobado es un **sistema de monitoreo de una sala térmica/técnica**, con un componente de hardware y otro de software.

### Objetivo

Monitorear continuamente:

- Temperatura.
- Humedad.
- Presencia de humo.

Y actuar localmente ante condiciones anormales mediante:

- Alarma audible.
- LEDs indicadores.
- Botón físico para silenciar la alarma.

Los datos también deben enviarse a un sistema web para visualización, almacenamiento histórico y registro de alertas.

### Fecha límite

**Lunes 2 de noviembre de 2026.**

El sistema debe estar funcional como máximo alrededor del **23 de octubre**, dejando la última semana para pruebas, correcciones, documentación y presentación.

---

# 2. Restricciones

- El proyecto es costeado por los estudiantes.
- Debe ser económicamente viable.
- Debe poder completarse antes de la fecha límite.
- Debe incluir hardware y software.
- Debe tener como mínimo 2 sensores y 2 actuadores.
- La alarma crítica debe funcionar localmente, sin depender del servidor.
- No se utilizará panel solar.
- No se debe sobreingenierizar el sistema.
- Las decisiones deben priorizar seguridad, confiabilidad, costo, mantenibilidad y viabilidad académica.

---

# 3. Hardware

## 3.1 Microcontrolador

**ESP32 DevKit / ESP32-WROOM-32**

Funciones:

- Lectura de sensores.
- Procesamiento de estados.
- Control de LEDs.
- Control de sirena mediante etapa de potencia.
- Lectura del botón.
- Wi-Fi.
- Comunicación MQTT.

El modelo exacto de la placa debe confirmarse si las conexiones dependen de una variante concreta.

---

## 3.2 Temperatura y humedad

Se ha decidido utilizar **SHT31** en lugar de DHT11, debido a que el proyecto requiere mayor calidad de medición y funcionamiento continuo.

Se contempla utilizar dos SHT31 si resulta conveniente por la distribución física de la sala.

Comunicación:

**I²C**

Mapa preliminar:

| ESP32 | SHT31 |
|---|---|
| GPIO 21 | SDA |
| GPIO 22 | SCL |
| 3.3 V | VCC |
| GND | GND |

Si se utilizan dos sensores:

- SHT31 #1 → dirección normalmente `0x44`
- SHT31 #2 → dirección normalmente `0x45`

La dirección depende del módulo y debe comprobarse en el modelo adquirido.

No asumir que todos los módulos comerciales tienen la misma distribución de pines o las mismas resistencias pull-up.

---

## 3.3 Sensor de humo

Se consideró inicialmente un **MQ-2**, pero el modelo definitivo todavía debe confirmarse.

Si se utiliza MQ-2 con salida analógica:

- No conectar directamente AO al ESP32 si la salida puede superar 3.3 V.
- Utilizar divisor de tensión.
- Se había considerado un divisor de 20 kΩ + 10 kΩ.
- GPIO34 es un candidato para ADC.

El divisor y los valores definitivos deben calcularse según el módulo exacto.

Importante: un MQ-2 no debe presentarse como equivalente a un detector de humo certificado para seguridad contra incendios. Es un sensor de prototipo/gases y su idoneidad debe evaluarse según el objetivo real.

---

## 3.4 Actuadores

### Sirena / alarma

Se contempla una sirena de 12 V DC, pero el modelo y corriente exactos aún deben confirmarse.

No conectar directamente al ESP32.

Arquitectura prevista:

ESP32 GPIO → resistencia de gate → MOSFET lógico → sirena.

Se habían considerado provisionalmente:

- IRLZ44N.
- 100 Ω en gate.
- 10 kΩ como pull-down.
- 1N4007 como protección.

Estos valores NO son definitivos hasta conocer tensión, corriente y naturaleza de la carga.

### LEDs

Tres LEDs:

- Verde → normal.
- Amarillo → advertencia.
- Rojo → crítico/alarma.

Se propone una resistencia independiente por LED:

**220 Ω** como valor inicial.

330 Ω también puede ser una alternativa.

---

## 3.5 Botón físico

Botón **Push Button normalmente abierto (NO)**.

Función:

- Silenciar/apagar la alarma audible.
- No debe borrar la condición de peligro.

Ejemplo:

Humo detectado → alarma activa → usuario pulsa botón → sirena silenciada, pero el sistema continúa mostrando/registrando humo.

Conexión propuesta:

GPIO27 → botón → GND

Software:

```cpp
pinMode(27, INPUT_PULLUP);
```

Estados:

- Sin pulsar → HIGH.
- Pulsado → LOW.

No se necesita una resistencia externa de pull-up si se utiliza correctamente la resistencia interna.

---

# 4. Mapa preliminar de GPIO

| GPIO | Función |
|---:|---|
| GPIO 21 | SDA I²C |
| GPIO 22 | SCL I²C |
| GPIO 34 | Sensor de humo analógico |
| GPIO 26 | Sirena/MOSFET |
| GPIO 25 | LED verde |
| GPIO 32 | LED amarillo |
| GPIO 33 | LED rojo |
| GPIO 27 | Botón |

Este mapa es preliminar y debe verificarse contra la placa ESP32 concreta.

---

# 5. Alimentación

Se descartó el panel solar.

Arquitectura inicialmente prevista:

**Fuente 12 V DC**

Una rama alimenta la sirena de 12 V.

Otra rama entra a un:

**LM2596**

para generar aproximadamente 5 V para el ESP32 y otros componentes que lo requieran.

Esquema conceptual:

```text
Fuente 12 V
   ├──→ Sirena 12 V
   │
   └──→ LM2596 → 5 V → ESP32
```

Antes de conectar el ESP32:

- Ajustar el LM2596.
- Medir su salida con multímetro.
- Verificar polaridad.
- Confirmar qué tensión acepta la placa concreta.

Se ha considerado añadir:

- Capacitor 100 µF / 25 V.
- Capacitores 100 nF de desacoplo.

Los valores finales dependen del circuito real.

No se ha cerrado todavía el sistema de respaldo energético. Si se incorpora batería/UPS, debe dimensionarse según consumo y autonomía requerida.

---

# 6. Principio arquitectónico fundamental

La **alarma local no debe depender del servidor**.

Debe funcionar:

```text
Sensor
  ↓
ESP32
  ↓
Lógica local
  ↓
LED + sirena
```

Aunque se pierda:

- Internet.
- Wi-Fi.
- Broker MQTT.
- Servidor Laravel.
- Base de datos.
- Dashboard.

El ESP32 debe continuar:

- leyendo sensores;
- procesando condiciones;
- activando LEDs;
- activando la sirena;
- leyendo el botón.

La comunicación con el backend es una función adicional y no el mecanismo que decide si la alarma física funciona.

---

# 7. Software

## 7.1 Firmware

Plataforma:

**ESP32 + C/C++**

Responsabilidades:

- Inicializar sensores.
- Leer temperatura.
- Leer humedad.
- Leer humo.
- Procesar estados.
- Controlar LEDs.
- Controlar sirena.
- Leer botón.
- Conectarse a Wi-Fi.
- Publicar datos MQTT.
- Gestionar reconexión.

Se recomienda organizar el firmware por módulos en lugar de concentrar toda la lógica en `loop()`.

Estructura conceptual:

```text
firmware/
├── sensors/
├── actuators/
├── button/
├── wifi/
├── mqtt/
└── main
```

---

# 8. MQTT

Tecnología elegida:

**MQTT**

Broker de desarrollo contemplado:

**Mosquitto**

Arquitectura:

```text
ESP32
  ↓
Wi-Fi
  ↓
MQTT Broker
  ↓
Laravel
```

Topics posibles:

```text
intalnet/sala01/telemetria
intalnet/sala01/estado
intalnet/sala01/alerta
```

Es preferible no crear topics innecesarios si un payload estructurado es suficiente.

Payload de ejemplo:

```json
{
  "device_id": "sala01",
  "temperature": 27.4,
  "humidity": 61.2,
  "smoke": false
}
```

El formato final puede modificarse.

Debe considerarse:

- QoS.
- Retención.
- autenticación.
- reconexión.
- validación del payload.

No utilizar MQTT simplemente por ser una tecnología IoT; debe mantenerse porque encaja con la comunicación entre ESP32 y backend.

---

# 9. Backend

Tecnología elegida:

**Laravel / PHP**

Laravel será responsable de:

- API.
- Lógica de negocio.
- Procesamiento de datos MQTT.
- Persistencia.
- Historial.
- Alertas.
- Usuarios.
- Configuración.
- Estado de dispositivos.

Endpoints preliminares:

```text
POST /api/readings
GET  /api/readings/latest
GET  /api/readings/history
GET  /api/alerts
GET  /api/status
GET  /api/devices
```

No son definitivos.

---

# 10. Autenticación y Jetstream

Se decidió utilizar:

**Laravel Jetstream**

en lugar de Breeze.

La razón es que el sistema tendrá una interfaz con usuarios y posibles funciones de administración, y Jetstream proporciona una base más completa de autenticación.

Stack frontend elegido/preferido:

**Jetstream + Livewire + Blade + Tailwind + Chart.js**

No se pretende utilizar React como frontend principal.

### Livewire

Se recomienda Livewire porque permite construir un dashboard interactivo manteniendo gran parte de la lógica dentro del ecosistema Laravel/PHP.

Usos previstos:

- Estado de sensores.
- Alertas.
- Formularios.
- Filtros.
- Administración.
- Configuración.

### Chart.js

Se utilizará para gráficas de:

- Temperatura.
- Humedad.
- Historial.

---

# 11. Dashboard

El dashboard debe mostrar como mínimo:

```text
MONITOREO SALA TÉRMICA

Temperatura
27.4 °C
NORMAL

Humedad
61 %
NORMAL

Humo
NO DETECTADO

Sistema
NORMAL
```

También:

- Estado de conexión del ESP32.
- Historial.
- Alertas.

Gráficas sugeridas:

- Última hora.
- 6 horas.
- 24 horas.
- 7 días.

La interfaz debe priorizar que un operador pueda identificar rápidamente si la sala está en estado normal o crítico.

---

# 12. Base de datos

Se contempla:

**MySQL**

Tablas preliminares:

## users

- id
- name
- email
- password
- role

## devices

- id
- name
- location
- status

## sensor_readings

- id
- device_id
- temperature
- humidity
- smoke
- created_at

## alerts

- id
- device_id
- type
- severity
- message
- created_at
- resolved_at

## system_config

Posibles parámetros:

- temperature_warning
- temperature_critical
- humidity_warning
- etc.

La estructura final debe normalizarse y adaptarse al uso real.

---

# 13. Estados del sistema

Estados propuestos:

### NORMAL

- LED verde.
- Sin alarma.

### ADVERTENCIA

- LED amarillo.
- Registro de condición.
- Sirena dependiendo de la regla establecida.

### CRÍTICO

- LED rojo.
- Sirena activa.

### HUMO

- LED rojo.
- Sirena activada inmediatamente.

El botón puede silenciar la sirena, pero no debe eliminar el estado crítico.

---

# 14. MVP obligatorio

El MVP debe tener:

- ESP32.
- Temperatura.
- Humedad.
- Humo.
- LEDs.
- Sirena.
- Botón físico.
- Lógica local de alarma.
- Wi-Fi.
- MQTT.
- Broker.
- Laravel.
- MySQL.
- Dashboard.
- Lecturas actuales.
- Historial básico.
- Registro de alertas.

---

# 15. Funciones secundarias

Solo después de terminar el MVP:

- WebSockets.
- Laravel Reverb.
- Usuarios avanzados.
- Roles.
- Configuración de límites desde web.
- Detección de desconexión.
- Reportes.
- Exportación de datos.
- Notificaciones.
- Buffer local de datos en ESP32.

---

# 16. Fuera del alcance

No priorizar:

- Panel solar.
- Aplicación móvil nativa.
- IA.
- Machine Learning.
- Predicción de fallos.
- Automatización del aire acondicionado.
- Integraciones empresariales externas.
- Arquitecturas distribuidas innecesariamente complejas.

Pueden plantearse como trabajo futuro.

---

# 17. Cronograma

## Semana 1 — 28 sep a 4 oct

### Requisitos y arquitectura

- Reunión/recolección de necesidades.
- Definición de requisitos funcionales.
- Definición de requisitos no funcionales.
- Arquitectura.
- Stack tecnológico.
- Cerrar decisiones principales.

**Hito: 4 de octubre — requisitos cerrados.**

---

## Semana 2 — 5 a 11 oct

### Hardware y firmware inicial

- Compra/recepción de componentes.
- Prueba individual de SHT31.
- Prueba del sensor de humo.
- Prueba de LEDs.
- Prueba del botón.
- Prueba de sirena.
- Prueba de alimentación.
- Montaje inicial.

**Hito: 11 de octubre — sensores y actuadores funcionando.**

---

## Semana 3 — 12 a 18 oct

### Firmware + MQTT + backend

- Firmware integrado.
- Wi-Fi.
- MQTT.
- Broker.
- Laravel.
- MySQL.
- API.
- Recepción de datos.

**Hito: 18 de octubre — ESP32 enviando datos y backend almacenándolos.**

---

## Semana 4 — 19 a 25 oct

### Dashboard + integración

- Dashboard MVP.
- Lecturas actuales.
- Historial.
- Alertas.
- Estado del dispositivo.
- Integración completa.

**Hito: 23 de octubre — sistema completo demostrable.**

---

## Semana 5 — 26 oct a 1 nov

### Pruebas y entrega

- Pruebas de estrés.
- Pruebas de pérdida de Wi-Fi.
- Pruebas de reinicio.
- Prueba física en Intalnet.
- Correcciones.
- Documentación.
- Capturas/fotografías.
- Presentación.
- Ensayo.

**Hito: 1 de noviembre — proyecto congelado y listo.**

---

## Entrega

**2 de noviembre de 2026.**

---

# 18. Fechas internas

| Fecha | Hito |
|---|---|
| 4 oct | Requisitos cerrados |
| 6 oct | Arquitectura cerrada |
| 9 oct | Componentes disponibles |
| 11 oct | Sensores/actuadores funcionando |
| 14 oct | Firmware base |
| 18 oct | ESP32 → MQTT → backend |
| 18 oct | BD + API |
| 21 oct | Dashboard MVP |
| 23 oct | Sistema integrado |
| 25 oct | Primera demostración |
| 27 oct | Pruebas de estrés |
| 29 oct | Prueba en Intalnet |
| 30 oct | Correcciones |
| 31 oct | Informe |
| 1 nov | Ensayo |
| 2 nov | Entrega |

---

# 19. Pruebas requeridas

Como mínimo:

1. Temperatura normal.
2. Temperatura elevada.
3. Temperatura crítica.
4. Humo detectado.
5. Activación de sirena.
6. Activación del LED rojo.
7. Pulsación del botón.
8. Silenciado de sirena.
9. Recuperación a estado normal.
10. Pérdida de Wi-Fi.
11. Recuperación de Wi-Fi.
12. Reinicio del ESP32.
13. Pérdida del servidor.
14. Funcionamiento continuo.
15. Datos inválidos.
16. Cobertura Wi-Fi.
17. Alimentación.
18. Integración completa.

---

# 20. Componentes preliminares

| Componente | Cantidad preliminar | Estado |
|---|---:|---|
| ESP32 DevKit / ESP32-WROOM-32 | 1 | Confirmar modelo |
| SHT31 | 2 | Propuesto |
| Sensor de humo | 1 | Modelo pendiente |
| Sirena | 1 | Modelo/corriente pendiente |
| LED verde | 1 | Propuesto |
| LED amarillo | 1 | Propuesto |
| LED rojo | 1 | Propuesto |
| Push button NO | 1 | Propuesto |
| MOSFET lógico | 1 | Modelo pendiente |
| Diodo de protección | 1 | Modelo pendiente |
| Resistencia 220 Ω | 3 | Propuesto para LEDs |
| Resistencia 10 kΩ | 1 | Pull-down provisional de MOSFET |
| Resistencia 100 Ω | 1 | Gate provisional |
| Resistencias para divisor ADC | 1 conjunto | Solo si se usa MQ-2 analógico |
| LM2596 | 1 | Propuesto |
| Fuente 12 V DC | 1 | Modelo pendiente |
| Capacitor 100 µF / 25 V | 1+ | Propuesto |
| Capacitores 100 nF | 2–3 | Propuesto |
| Protoboard/cables/conectores | varios | Necesarios |

Los valores y modelos que estén marcados como provisionales deben verificarse antes de comprar.

---

# 21. Reglas técnicas para futuras consultas

Cuando se soliciten conexiones eléctricas:

1. Confirmar el modelo exacto si la conexión depende de él.
2. Verificar tensión.
3. Verificar corriente.
4. Verificar niveles lógicos.
5. Calcular resistencias.
6. Determinar si hace falta transistor/MOSFET/relé.
7. Considerar protección contra cargas inductivas.
8. Revisar GND común.
9. Revisar alimentación.
10. Evitar conectar cargas directamente al ESP32.

Cuando se solicite código:

- Indicar archivo.
- Indicar ubicación.
- Indicar dependencias.
- Indicar configuración.
- Dar código completo cuando sea necesario.
- Explicar cómo ejecutar.
- Explicar cómo probar.

Cuando se solicite Laravel:

- Mantener la arquitectura coherente con Laravel + Jetstream + Livewire + Blade.
- Evitar paquetes innecesarios.
- Separar responsabilidades.
- Utilizar migrations, models, controllers/services y validación apropiadamente.

Cuando se solicite MQTT:

- Explicar broker.
- Publisher.
- Subscriber.
- Topics.
- Payload.
- QoS.
- Reconexión.
- Autenticación si corresponde.

Cuando se solicite electrónica:

- No inventar especificaciones.
- Si falta un modelo, indicarlo.
- Diferenciar prototipo académico de sistema certificado.
- Priorizar seguridad eléctrica.

---

# 22. Regla principal para la IA

Usar este documento como contexto permanente del proyecto.

No cambiar componentes o arquitectura arbitrariamente.

Si una decisión anterior debe cambiarse:

1. Explicar qué se cambiaría.
2. Explicar por qué.
3. Indicar ventajas.
4. Indicar desventajas.
5. Indicar impacto en costo.
6. Indicar impacto en tiempo.
7. Indicar qué partes del proyecto habría que modificar.

Si falta información crítica:

- No inventarla.
- Identificar exactamente qué dato falta.
- Si es posible, proporcionar una solución provisional claramente marcada.

Prioridad general:

**seguridad → funcionamiento → simplicidad → mantenibilidad → costo → funcionalidades adicionales.**

El objetivo no es crear el sistema más complejo posible, sino un sistema suficientemente robusto, demostrable y técnicamente defendible antes del 2 de noviembre de 2026.
