<x-app-layout>
    <div class="flex flex-col w-full">
        <!-- BANNER DE ESTADO GENERAL INDUSTRIAL (CRÍTICO) -->
        <section class="bg-error-container/30 border border-error/60 rounded p-space-md flex flex-col lg:flex-row items-start lg:items-center justify-between gap-space-md shadow-lg shadow-black/40">
            <div class="flex items-start md:items-center gap-space-md min-w-0">
                <div class="flex items-center justify-center w-10 h-10 rounded bg-error/20 border border-error/70 shrink-0 text-error animate-pulse">
                    <span class="material-symbols-outlined text-[24px]">crisis_alert</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-space-sm flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-error text-surface-container-lowest font-label-caps text-label-caps tracking-widest font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                            ESTADO GENERAL: CRÍTICO
                        </span>
                        <span class="font-code-xs text-code-xs text-error font-semibold uppercase tracking-wider font-mono">PRIORIDAD 1 [DISPATCH-AUTO]</span>
                        <span class="text-outline-variant font-code-xs text-code-xs">|</span>
                        <div class="flex items-center gap-1 font-data-metric text-code-sm text-error font-bold font-mono">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            <span id="incident-timer">T+ 00:14:32</span>
                        </div>
                    </div>
                    <div class="font-body-md text-body-md text-on-surface mt-1 truncate">
                        Alerta térmica <span class="text-error font-semibold">Sala A</span> - Umbral superado en <span class="font-code-xs text-code-xs text-on-surface-variant font-semibold font-mono">SHT31-D (Sensor 01)</span> | Temp: <span class="font-code-sm text-code-sm font-bold text-error font-mono" id="banner-temp">42.8°C</span> (&gt; 38.0°C MÁX)
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-space-sm self-end lg:self-center shrink-0">
                <button class="flex items-center gap-1.5 border border-error/80 bg-error-container/60 hover:bg-error-container text-error font-label-caps text-label-caps px-space-md py-2 rounded transition-colors uppercase tracking-wider active:scale-95" id="btn-silence" type="button">
                    <span class="material-symbols-outlined text-[16px]">notifications_off</span>
                    <span>Silenciar Alarma</span>
                </button>
                <button class="flex items-center gap-1.5 bg-error hover:bg-error/90 text-surface-container-lowest font-label-caps text-label-caps font-bold px-space-lg py-2 rounded uppercase tracking-wider transition-colors shadow-md shadow-error/30 active:scale-95" id="btn-ack" type="button">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>Reconocer Incidente</span>
                </button>
            </div>
        </section>

        <!-- MATRIX GRID DE 6 SENSORES EN TIEMPO REAL -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-space-sm my-space-md">
            <!-- Tarjeta 1: Temp Sala Principal (CRÍTICO) -->
            <div class="bg-error-container/20 border border-error/70 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-12 h-12 bg-gradient-to-bl from-error/30 to-transparent pointer-events-none"></div>
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">CAN 0x44</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-error text-surface-container-lowest font-label-caps text-[9px] uppercase font-bold animate-pulse">CRÍTICO</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Temp. Sala Principal</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">SHT31-D // MOD_01</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-error leading-none" id="grid-temp">42.8</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">°C</div>
                </div>
                <!-- Mini Sparkline Crítico Ascendente -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-error fill-error/20" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,22 L15,20 L30,21 L45,16 L60,18 L75,9 L90,5 L100,2 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,22 L15,20 L30,21 L45,16 L60,18 L75,9 L90,5 L100,2" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-error font-medium font-mono">
                        <span>LÍM: &gt; 38.0°C</span>
                        <span class="flex items-center"><span class="material-symbols-outlined text-[12px]">trending_up</span>+3.4°/h</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Humo / Combustión (ADVERTENCIA) -->
            <div class="bg-tertiary-container/10 border border-tertiary/70 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">ADC 0x12</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-tertiary text-on-tertiary font-label-caps text-[9px] uppercase font-bold">ADVERTENCIA</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Humo / Combustión</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">MQ-2 // ANALOG_CH1</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-tertiary leading-none" id="grid-smoke">485</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">ppm</div>
                </div>
                <!-- Mini Sparkline Advertencia Pico -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-tertiary fill-tertiary/20" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,24 L20,23 L35,22 L50,19 L65,8 L80,12 L95,6 L100,7 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,24 L20,23 L35,22 L50,19 L65,8 L80,12 L95,6 L100,7" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-tertiary font-medium font-mono">
                        <span>LÍM: &gt; 400 ppm</span>
                        <span class="flex items-center"><span class="material-symbols-outlined text-[12px]">keyboard_double_arrow_up</span>PICO</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Humedad Relativa (ADVERTENCIA) -->
            <div class="bg-tertiary-container/10 border border-tertiary/60 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">CAN 0x44</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-tertiary/90 text-on-tertiary font-label-caps text-[9px] uppercase font-bold">ALERTA</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Humedad Relativa</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">SHT31-D // CAN_BUS</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-tertiary-fixed leading-none" id="grid-hum">68.4</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">%</div>
                </div>
                <!-- Mini Sparkline Humedad -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-tertiary-fixed fill-tertiary-fixed/15" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,18 L25,17 L40,15 L60,14 L80,11 L100,9 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,18 L25,17 L40,15 L60,14 L80,11 L100,9" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-tertiary font-medium font-mono">
                        <span>LÍM: &gt; 65.0%</span>
                        <span class="text-outline">ESTABLE</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4: Temp Rack Core (NOMINAL) -->
            <div class="bg-surface-container-low border border-secondary/40 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">CAN 0x45</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-secondary/20 text-secondary font-label-caps text-[9px] uppercase font-bold">NOMINAL</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Temp. Rack Core</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">SHT31-D // MOD_02</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-secondary leading-none" id="grid-core">31.2</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">°C</div>
                </div>
                <!-- Mini Sparkline Verde Plano -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-secondary fill-secondary/15" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,15 L20,16 L40,14 L60,15 L80,14 L100,15 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,15 L20,16 L40,14 L60,15 L80,14 L100,15" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-secondary font-medium font-mono">
                        <span>LÍM: &lt; 35.0°C</span>
                        <span class="text-secondary-fixed">ÓPTIMO</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 5: Flujo de Aire Extractor (NOMINAL) -->
            <div class="bg-surface-container-low border border-secondary/40 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">PWM TACO</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-secondary/20 text-secondary font-label-caps text-[9px] uppercase font-bold">NOMINAL</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Flujo Extractor</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">TACO_EXT // PWM</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-secondary leading-none" id="grid-flow">18.5</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">m³/h</div>
                </div>
                <!-- Mini Sparkline Verde Flujo -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-secondary fill-secondary/15" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,14 L20,12 L40,15 L60,11 L80,13 L100,12 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,14 L20,12 L40,15 L60,11 L80,13 L100,12" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-secondary font-medium font-mono">
                        <span>LÍM: &gt; 15.0 m³/h</span>
                        <span class="text-secondary">100% PWM</span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 6: Voltaje Bus DC / UPS (NOMINAL) -->
            <div class="bg-surface-container-low border border-primary/40 rounded p-space-sm flex flex-col justify-between relative overflow-hidden group">
                <div class="flex flex-col gap-0.5">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">I2C 0x40</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-primary/20 text-primary font-label-caps text-[9px] uppercase font-bold">NOMINAL</span>
                    </div>
                    <div class="font-headline-sm text-headline-sm text-on-surface truncate">Voltaje Bus DC/UPS</div>
                    <div class="font-code-xs text-code-xs text-on-surface-variant truncate font-mono">INA219 // VOLT_SENSE</div>
                </div>
                <div class="my-space-xs flex items-baseline justify-between font-mono">
                    <div class="font-data-display text-data-display text-primary leading-none" id="grid-volt">228.4</div>
                    <div class="font-code-sm text-code-sm text-on-surface-variant font-bold">V</div>
                </div>
                <!-- Mini Sparkline Cian Voltaje -->
                <div class="flex flex-col gap-1">
                    <div class="w-full h-7">
                        <svg class="w-full h-full stroke-primary fill-primary/15" preserveaspectratio="none" viewbox="0 0 100 28">
                            <path d="M0,16 L18,17 L36,15 L54,16 L72,16 L90,14 L100,15 L100,28 L0,28 Z" stroke-width="0"></path>
                            <path d="M0,16 L18,17 L36,15 L54,16 L72,16 L90,14 L100,15" fill="none" stroke-linecap="round" stroke-width="1.8"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between font-code-xs text-code-xs text-primary font-medium font-mono">
                        <span>NOM: 220V ±5%</span>
                        <span class="text-primary-fixed">SIN RUIDO</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- GRÁFICA DE TELEMETRÍA EN TIEMPO REAL SCADA -->
        <section class="bg-surface-container-lowest border border-outline-variant/40 rounded p-space-md mb-space-md flex flex-col">
            <!-- Header de telemetría y controles técnicos -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-space-sm border-b border-outline-variant/60 pb-space-sm">
                <div class="flex items-center gap-space-md">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary animate-ping"></span>
                            <span class="font-headline-md text-headline-md text-on-surface uppercase tracking-wider">Telemetría en Tiempo Real [Sala Térmica A]</span>
                        </div>
                        <span class="font-code-xs text-code-xs text-outline tracking-wider font-mono">MOTOR DE ADQUISICIÓN: BUFFER DMA // MUESTREO: 500ms // 120 MUESTRAS EN PANTALLA</span>
                    </div>
                </div>

                <!-- Filtros de canal y Time Range Selector -->
                <div class="flex flex-wrap items-center gap-space-xs self-stretch lg:self-auto justify-end">
                    <div class="flex items-center gap-1 bg-surface-container-low p-0.5 rounded border border-outline-variant/40">
                        <button class="channel-toggle px-2 py-1 rounded font-code-xs text-code-xs font-semibold bg-error/20 text-error border border-error/50 flex items-center gap-1" data-channel="temp" type="button">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            TEMP SHT31 [ON]
                        </button>
                        <button class="channel-toggle px-2 py-1 rounded font-code-xs text-code-xs font-semibold bg-tertiary/20 text-tertiary border border-tertiary/50 flex items-center gap-1" data-channel="gas" type="button">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                            MQ2 GAS [ON]
                        </button>
                        <button class="channel-toggle px-2 py-1 rounded font-code-xs text-code-xs font-semibold bg-primary/20 text-primary border border-primary/50 flex items-center gap-1" data-channel="hum" type="button">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            HUMEDAD [ON]
                        </button>
                        <button class="channel-toggle px-2 py-1 rounded font-code-xs text-code-xs text-outline border border-transparent hover:text-on-surface flex items-center gap-1" data-channel="pwm" type="button">
                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                            EXTRACTOR PWM [OFF]
                        </button>
                    </div>
                    <div class="flex items-center bg-surface-container-low p-0.5 rounded border border-outline-variant/40 font-code-xs text-code-xs font-bold font-mono">
                        <button class="time-range px-2 py-1 rounded text-outline hover:text-on-surface" type="button">5M</button>
                        <button class="time-range px-2 py-1 rounded bg-primary text-on-primary" type="button">15M</button>
                        <button class="time-range px-2 py-1 rounded text-outline hover:text-on-surface" type="button">1H</button>
                        <button class="time-range px-2 py-1 rounded text-outline hover:text-on-surface" type="button">24H</button>
                    </div>
                </div>
            </div>

            <!-- Technical Scope Display Canvas / Vector Area -->
            <div class="relative w-full h-72 my-space-sm bg-surface-container-lowest overflow-hidden rounded border border-outline-variant/30 flex items-center justify-center">
                <!-- Retícula milimétrica estilo osciloscopio CRT / SCADA -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern height="16" id="scada-grid-small" patternunits="userSpaceOnUse" width="16">
                            <path d="M 16 0 L 0 0 0 16" fill="none" stroke="rgba(255, 255, 255, 0.03)" stroke-width="0.75"></path>
                        </pattern>
                        <pattern height="80" id="scada-grid-major" patternunits="userSpaceOnUse" width="80">
                            <rect fill="url(#scada-grid-small)" height="80" width="80"></rect>
                            <path d="M 80 0 L 0 0 0 80" fill="none" stroke="rgba(76, 215, 246, 0.08)" stroke-width="1"></path>
                        </pattern>
                        <lineargradient id="tempGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#ffb4ab" stop-opacity="0.4"></stop>
                            <stop offset="70%" stop-color="#ffb4ab" stop-opacity="0.05"></stop>
                            <stop offset="100%" stop-color="#ffb4ab" stop-opacity="0"></stop>
                        </lineargradient>
                        <lineargradient id="gasGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#ffb95f" stop-opacity="0.3"></stop>
                            <stop offset="100%" stop-color="#ffb95f" stop-opacity="0"></stop>
                        </lineargradient>
                        <lineargradient id="humGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#4cd7f6" stop-opacity="0.2"></stop>
                            <stop offset="100%" stop-color="#4cd7f6" stop-opacity="0"></stop>
                        </lineargradient>
                    </defs>
                    <rect fill="url(#scada-grid-major)" height="100%" width="100%"></rect>
                </svg>

                <!-- Eje Y con marcas métricas de ingeniería -->
                <div class="absolute left-2 top-2 bottom-6 flex flex-col justify-between font-code-xs text-[10px] text-outline select-none pointer-events-none z-10 font-mono">
                    <span class="text-error font-bold">50.0°C [MÁX CANAL]</span>
                    <span class="text-error">42.8°C [ACTUAL]</span>
                    <span class="text-tertiary">38.0°C [UMBRAL CRÍTICO]</span>
                    <span>30.0°C</span>
                    <span>20.0°C</span>
                    <span>10.0°C [MIN]</span>
                </div>

                <!-- Umbral Horizontal Crítico 38.0°C -->
                <div class="absolute left-0 right-0 top-[28%] border-b border-dashed border-error/70 flex items-center justify-end px-3 select-none pointer-events-none z-10">
                    <span class="font-code-xs text-code-xs bg-error text-surface-container-lowest px-1.5 py-0.2 rounded font-bold uppercase tracking-wider font-mono">LÍMITE MÁXIMO SEGURIDAD: 38.0°C</span>
                </div>

                <!-- Eje de marcas temporales X -->
                <div class="absolute left-16 right-4 bottom-1 flex justify-between font-code-xs text-[10px] text-outline select-none pointer-events-none font-mono">
                    <span>-15:00m</span>
                    <span>-12:30m</span>
                    <span>-10:00m</span>
                    <span>-07:30m</span>
                    <span>-05:00m</span>
                    <span>-02:30m</span>
                    <span class="text-primary font-bold">AHORA [LIVE]</span>
                </div>

                <!-- Curvas de Ondas SVG de Datos en Tiempo Real -->
                <svg class="absolute inset-0 w-full h-full pl-16 pr-4 pt-4 pb-7" preserveaspectratio="none" viewbox="0 0 1000 240">
                    <!-- Curva MQ-2 GAS (Amarillo) -->
                    <path d="M0,170 C150,165 240,175 350,160 C420,150 490,140 560,110 C620,80 690,95 780,105 C850,115 920,85 1000,75 L1000,240 L0,240 Z" fill="url(#gasGradient)"></path>
                    <path d="M0,170 C150,165 240,175 350,160 C420,150 490,140 560,110 C620,80 690,95 780,105 C850,115 920,85 1000,75" fill="none" stroke="#ffb95f" stroke-linecap="round" stroke-width="2"></path>
                    <!-- Curva Humedad (Cian) -->
                    <path d="M0,130 C120,128 220,135 340,125 C460,120 580,110 700,95 C820,85 910,90 1000,80 L1000,240 L0,240 Z" fill="url(#humGradient)"></path>
                    <path d="M0,130 C120,128 220,135 340,125 C460,120 580,110 700,95 C820,85 910,90 1000,80" fill="none" stroke="#4cd7f6" stroke-dasharray="4,2" stroke-width="1.5"></path>
                    <!-- Curva Temperatura SHT31 (Rojo/Error con Glow ascendente por encima de umbral) -->
                    <path d="M0,145 C120,148 220,140 320,132 C410,125 510,115 620,85 C710,60 810,55 900,42 C940,36 970,30 1000,24 L1000,240 L0,240 Z" fill="url(#tempGradient)"></path>
                    <path d="M0,145 C120,148 220,140 320,132 C410,125 510,115 620,85 C710,60 810,55 900,42 C940,36 970,30 1000,24" fill="none" stroke="#ffb4ab" stroke-linecap="round" stroke-width="2.5"></path>
                    <!-- Marcador de punto activo actual -->
                    <circle class="animate-ping" cx="1000" cy="24" fill="#ffb4ab" opacity="0.8" r="5"></circle>
                    <circle cx="1000" cy="24" fill="#690005" r="4" stroke="#ffb4ab" stroke-width="2"></circle>
                </svg>
            </div>

            <!-- Barra de Telemetría Inferior - Estadísticas de Rendimiento CAN/Sensores -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-space-xs pt-space-xs border-t border-outline-variant/60 font-code-xs text-code-xs font-mono">
                <div class="flex items-center justify-between p-space-xs rounded bg-surface-container-low border border-outline-variant/40">
                    <span class="text-outline uppercase">Temp Media (15m):</span>
                    <span class="text-on-surface font-semibold">37.4°C</span>
                </div>
                <div class="flex items-center justify-between p-space-xs rounded bg-surface-container-low border border-outline-variant/40">
                    <span class="text-outline uppercase">Pico Registrado:</span>
                    <span class="text-error font-bold">43.1°C [14:21:40]</span>
                </div>
                <div class="flex items-center justify-between p-space-xs rounded bg-surface-container-low border border-outline-variant/40">
                    <span class="text-outline uppercase">RMS Ruido Señal:</span>
                    <span class="text-secondary font-semibold">0.04 σ [NOMINAL]</span>
                </div>
                <div class="flex items-center justify-between p-space-xs rounded bg-surface-container-low border border-outline-variant/40">
                    <span class="text-outline uppercase">Bus CAN Tráfico:</span>
                    <span class="text-primary font-semibold">14,892 pkts/s (0 drop)</span>
                </div>
            </div>
        </section>

        <!-- PANEL DE CONTROL DE ACTUADORES & RELÉS DE POTENCIA -->
        <section class="bg-surface-container-lowest border border-outline-variant/40 rounded p-space-md mb-space-md flex flex-col">
            <div class="flex items-center justify-between pb-space-sm border-b border-outline-variant/60">
                <div class="flex items-center gap-space-xs text-primary">
                    <span class="material-symbols-outlined text-[18px]">toggle_on</span>
                    <span class="font-headline-md text-headline-md uppercase tracking-wider text-on-surface">Control de Relés y Actuadores en Campo (ESP32)</span>
                </div>
                <span class="font-code-xs text-code-xs text-secondary bg-surface-container px-2 py-0.5 rounded font-mono">INTERLOCK AUTO: HABILITADO</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md pt-space-sm">
                <!-- Relé 1: Sirena Local -->
                <div class="bg-surface-container-low p-space-sm rounded border border-outline-variant/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-headline-sm text-headline-sm text-on-surface">Sirena Local</span>
                        <span class="font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-error text-surface-container-lowest font-bold font-mono" id="status-sirena">ACTIVA</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-outline font-mono">GPIO 25 // 12V 105dB</span>
                    <button class="mt-space-sm py-1.5 px-space-md bg-surface-container-high hover:bg-surface-bright text-on-surface font-label-caps text-label-caps uppercase rounded flex items-center justify-center gap-1 border border-outline-variant/40 transition-colors"
                        onclick="toggleRelay('sirena', this)" type="button">
                        <span class="material-symbols-outlined text-[16px]">power_settings_new</span>
                        <span>Alternar Sirena</span>
                    </button>
                </div>

                <!-- Relé 2: Extractor Forzado N2 -->
                <div class="bg-surface-container-low p-space-sm rounded border border-outline-variant/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-headline-sm text-headline-sm text-on-surface">Extractor HVAC</span>
                        <span class="font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-secondary text-surface-container-lowest font-bold font-mono" id="status-extractor">100% PWM</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-outline font-mono">GPIO 19 // RELE_02</span>
                    <button class="mt-space-sm py-1.5 px-space-md bg-surface-container-high hover:bg-surface-bright text-on-surface font-label-caps text-label-caps uppercase rounded flex items-center justify-center gap-1 border border-outline-variant/40 transition-colors"
                        onclick="toggleRelay('extractor', this)" type="button">
                        <span class="material-symbols-outlined text-[16px]">fan</span>
                        <span>Modo Purga HVAC</span>
                    </button>
                </div>

                <!-- Relé 3: Válvula de Purga Gas -->
                <div class="bg-surface-container-low p-space-sm rounded border border-outline-variant/40 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-headline-sm text-headline-sm text-on-surface">Válvula Purga EV-4</span>
                        <span class="font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-tertiary text-on-tertiary font-bold font-mono" id="status-valvula">ABIERTA</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-outline font-mono">GPIO 18 // SOLENOIDE</span>
                    <button class="mt-space-sm py-1.5 px-space-md bg-surface-container-high hover:bg-surface-bright text-on-surface font-label-caps text-label-caps uppercase rounded flex items-center justify-center gap-1 border border-outline-variant/40 transition-colors"
                        onclick="toggleRelay('valvula', this)" type="button">
                        <span class="material-symbols-outlined text-[16px]">valve</span>
                        <span>Cerrar Válvula</span>
                    </button>
                </div>

                <!-- Relé 4: Corte General Rack (E-STOP) -->
                <div class="bg-surface-container-low p-space-sm rounded border border-error/50 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-headline-sm text-headline-sm text-error">E-Stop Térmico</span>
                        <span class="font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-surface-container text-secondary font-bold font-mono" id="status-estop">STANDBY</span>
                    </div>
                    <span class="font-code-xs text-code-xs text-outline font-mono">CONTACTOR TRIFÁSICO</span>
                    <button class="mt-space-sm py-1.5 px-space-md bg-error hover:bg-error/90 text-surface-container-lowest font-label-caps text-label-caps uppercase rounded flex items-center justify-center gap-1 font-bold shadow-md transition-colors"
                        onclick="triggerEStop(this)" type="button">
                        <span class="material-symbols-outlined text-[16px]">dangerous</span>
                        <span>Disparo E-Stop</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- TABLA DE REGISTRO HISTÓRICO DE INCIDENTES Y DESPACHOS INDUSTRIAL -->
        <section class="bg-surface-container-lowest border border-outline-variant/40 rounded p-space-md flex flex-col">
            <!-- Header de bitácora y filtros de severidad -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-space-sm pb-space-sm border-b border-outline-variant/60">
                <div class="flex items-center gap-space-sm flex-wrap">
                    <span class="font-headline-md text-headline-md text-on-surface uppercase tracking-wider">Registro Histórico de Incidentes y Despachos (Últimas 24H)</span>
                    <div class="flex items-center gap-1 font-code-xs text-code-xs px-2 py-0.5 rounded bg-surface-container-high border border-outline-variant/40 font-mono">
                        <span class="text-error font-bold">3 ACTIVOS</span>
                        <span class="text-outline">/</span>
                        <span class="text-secondary font-bold">14 RESUELTOS</span>
                    </div>
                </div>

                <div class="flex items-center gap-space-xs w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <span class="material-symbols-outlined absolute left-2 top-2 text-[16px] text-outline">search</span>
                        <input class="w-full bg-surface-container-low border border-outline-variant/40 rounded pl-8 pr-2 py-1 font-code-xs text-code-xs text-on-surface placeholder:text-outline focus:outline-none focus:border-primary font-mono" id="incident-filter" placeholder="FILTRAR NODO, TIPO O SENSOR..." type="text" />
                    </div>
                    <select class="bg-surface-container-low border border-outline-variant/40 rounded px-2 py-1 font-code-xs text-code-xs text-on-surface focus:outline-none focus:border-primary font-mono" id="severity-select" onchange="filterSeverity(this.value)">
                        <option value="ALL">TODAS SEVERIDADES</option>
                        <option value="CRÍTICO">SÓLO CRÍTICOS</option>
                        <option value="ADVERTENCIA">ADVERTENCIAS</option>
                        <option value="NOMINAL">NOMINALES</option>
                    </select>
                </div>
            </div>

            <!-- Contenedor SCADA de Tabla con Scroll Horizontal Responsivo -->
            <div class="overflow-x-auto mt-space-sm">
                <table class="w-full text-left border-collapse select-text">
                    <thead>
                        <tr class="border-b border-outline-variant/40 bg-surface-container-low font-headline-sm text-headline-sm uppercase tracking-wider text-outline text-[13px]">
                            <th class="py-2 px-space-sm">ID INCIDENTE</th>
                            <th class="py-2 px-space-sm">TIMESTAMP (UTC-5)</th>
                            <th class="py-2 px-space-sm">SUBSISTEMA / NODO</th>
                            <th class="py-2 px-space-sm">TIPO ALERTA</th>
                            <th class="py-2 px-space-sm text-right">VALOR LEÍDO</th>
                            <th class="py-2 px-space-sm text-right">UMBRAL</th>
                            <th class="py-2 px-space-sm text-center">SEVERIDAD</th>
                            <th class="py-2 px-space-sm">ESTADO / ACCIÓN DE CONTROL</th>
                        </tr>
                    </thead>
                    <tbody class="font-code-xs text-code-xs divide-y divide-outline-variant/30 font-mono" id="incidents-body">
                        <!-- Fila 1: Sobretemperatura Crítica -->
                        <tr class="hover:bg-surface-container-high transition-colors bg-error-container/10">
                            <td class="py-2 px-space-sm font-bold text-error">#INC-8921</td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">{{ date('Y-m-d H:i:s') }}</td>
                            <td class="py-2 px-space-sm text-on-surface font-semibold">Sala Térmica A / SHT31-D</td>
                            <td class="py-2 px-space-sm text-error font-bold">Sobretemperatura Crítica</td>
                            <td class="py-2 px-space-sm text-right font-bold text-error font-data-metric text-[14px]">42.8 °C</td>
                            <td class="py-2 px-space-sm text-right text-outline">&gt; 38.0 °C</td>
                            <td class="py-2 px-space-sm text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-error text-surface-container-lowest font-label-caps text-label-caps font-bold animate-pulse">
                                    <span class="w-1 h-1 rounded-full bg-white"></span>
                                    CRÍTICO
                                </span>
                            </td>
                            <td class="py-2 px-space-sm text-error font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] animate-spin">sync</span>
                                    <span>Despacho HVAC Nivel 2 [EN EJECUCIÓN]</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila 2: Concentración VOC/Gas -->
                        <tr class="hover:bg-surface-container-high transition-colors bg-tertiary-container/5">
                            <td class="py-2 px-space-sm font-bold text-tertiary">#INC-8920</td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">{{ date('Y-m-d', strtotime('-5 minutes')) }} 14:19:45</td>
                            <td class="py-2 px-space-sm text-on-surface font-semibold">Extractor 02 / MQ-2</td>
                            <td class="py-2 px-space-sm text-tertiary font-bold">Concentración VOC/Gas</td>
                            <td class="py-2 px-space-sm text-right font-bold text-tertiary font-data-metric text-[14px]">485 ppm</td>
                            <td class="py-2 px-space-sm text-right text-outline">&gt; 400 ppm</td>
                            <td class="py-2 px-space-sm text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-tertiary text-on-tertiary font-label-caps text-label-caps font-bold">
                                    ADVERTENCIA
                                </span>
                            </td>
                            <td class="py-2 px-space-sm text-tertiary font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">air</span>
                                    <span>Purga Automática Activada (Válvula EV-4)</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila 3: Caída Presión Chiller -->
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="py-2 px-space-sm font-bold text-tertiary-fixed-dim">#INC-8919</td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">{{ date('Y-m-d', strtotime('-30 minutes')) }} 13:50:12</td>
                            <td class="py-2 px-space-sm text-on-surface font-semibold">Chiller Auxiliar B</td>
                            <td class="py-2 px-space-sm text-on-surface">Caída Presión Refrigerante</td>
                            <td class="py-2 px-space-sm text-right font-bold text-tertiary font-data-metric text-[14px]">3.1 bar</td>
                            <td class="py-2 px-space-sm text-right text-outline">&lt; 3.5 bar</td>
                            <td class="py-2 px-space-sm text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-tertiary/20 text-tertiary border border-tertiary/40 font-label-caps text-label-caps font-semibold">
                                    ADVERTENCIA
                                </span>
                            </td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">sms</span>
                                    <span>Notificación SMS Enviada (Técnico Guardia)</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila 4: Batería UPS -->
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="py-2 px-space-sm font-bold text-secondary">#INC-8915</td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">{{ date('Y-m-d', strtotime('-3 hours')) }} 11:05:40</td>
                            <td class="py-2 px-space-sm text-on-surface font-semibold">Rack UPS Principal</td>
                            <td class="py-2 px-space-sm text-on-surface">Batería en Descarga Test</td>
                            <td class="py-2 px-space-sm text-right font-bold text-secondary font-data-metric text-[14px]">219.0 V</td>
                            <td class="py-2 px-space-sm text-right text-outline">&lt; 215.0 V</td>
                            <td class="py-2 px-space-sm text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-secondary/20 text-secondary font-label-caps text-label-caps font-semibold">
                                    NOMINAL
                                </span>
                            </td>
                            <td class="py-2 px-space-sm text-secondary font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                    <span>Prueba Rutina Finalizada [CONFORME]</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila 5: Sensor Infrarrojo -->
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="py-2 px-space-sm font-bold text-primary">#INC-8908</td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">{{ date('Y-m-d', strtotime('-5 hours')) }} 09:12:15</td>
                            <td class="py-2 px-space-sm text-on-surface font-semibold">Sensor SHT31 Secundario</td>
                            <td class="py-2 px-space-sm text-on-surface">Deriva de Señal Analógica</td>
                            <td class="py-2 px-space-sm text-right font-bold text-primary font-data-metric text-[14px]">0.02 V</td>
                            <td class="py-2 px-space-sm text-right text-outline">Discrepancia</td>
                            <td class="py-2 px-space-sm text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-primary/20 text-primary font-label-caps text-label-caps font-semibold">
                                    INFORMATIVO
                                </span>
                            </td>
                            <td class="py-2 px-space-sm text-on-surface-variant font-medium">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-primary">build</span>
                                    <span>Auto-calibración completada por firmware ESP32</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer de Paginación SCADA & Diagnóstico de Registros -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-space-xs mt-space-sm pt-space-xs border-t border-outline-variant/60 font-code-xs text-code-xs text-outline font-mono">
                <div class="flex items-center gap-space-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    <span>REGISTRO DB LOCAL: SQLITE WAL [BUFFER SINCRONIZADO CON MAESTRO INTALNET]</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>Mostrando 5 de 17 eventos registrados</span>
                    <button class="px-2 py-0.5 rounded bg-surface-container-low border border-outline-variant/40 hover:text-on-surface transition-colors" onclick="alert('Exportando búfer WAL a CSV...');" type="button">
                        EXPORTAR WAL (CSV)
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Live SCADA Dynamic Script -->
    <script>
        (function() {
            // Silenciar Alarma
            const btnSilence = document.getElementById('btn-silence');
            if (btnSilence) {
                btnSilence.addEventListener('click', function() {
                    const isSilenced = this.getAttribute('data-silenced') === 'true';
                    if (!isSilenced) {
                        this.setAttribute('data-silenced', 'true');
                        this.classList.remove('bg-error-container/60', 'text-error', 'border-error/80');
                        this.classList.add('bg-surface-container-high', 'text-outline', 'border-outline-variant/40');
                        this.innerHTML = '<span class="material-symbols-outlined text-[16px]">volume_off</span><span>ALARMA SILENCIADA</span>';
                    } else {
                        this.setAttribute('data-silenced', 'false');
                        this.classList.add('bg-error-container/60', 'text-error', 'border-error/80');
                        this.classList.remove('bg-surface-container-high', 'text-outline', 'border-outline-variant/40');
                        this.innerHTML = '<span class="material-symbols-outlined text-[16px]">notifications_off</span><span>SILENCIAR ALARMA</span>';
                    }
                });
            }

            // Reconocer Incidente
            const btnAck = document.getElementById('btn-ack');
            if (btnAck) {
                btnAck.addEventListener('click', function() {
                    this.classList.remove('bg-error', 'hover:bg-error/90');
                    this.classList.add('bg-secondary-container', 'text-on-secondary-container');
                    this.innerHTML = '<span class="material-symbols-outlined text-[16px]">task_alt</span><span>INCIDENTE RECONOCIDO</span>';
                });
            }

            // Toggles de Canal Gráfica
            const channelButtons = document.querySelectorAll('.channel-toggle');
            channelButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const isOff = this.innerText.includes('[OFF]');
                    const name = this.innerText.split('[')[0].trim();
                    const dot = this.querySelector('span');
                    if (isOff) {
                        this.classList.remove('text-outline', 'border-transparent');
                        this.classList.add('bg-surface-container-high', 'border-outline-variant', 'text-on-surface');
                        dot.classList.remove('bg-outline');
                        dot.classList.add('bg-secondary');
                        this.lastChild.textContent = ' ' + name + ' [ON]';
                    } else {
                        this.classList.remove('bg-surface-container-high', 'text-on-surface', 'bg-error/20', 'bg-tertiary/20', 'bg-primary/20', 'text-error', 'text-tertiary', 'text-primary');
                        this.classList.add('text-outline', 'border-transparent');
                        dot.classList.remove('bg-error', 'bg-tertiary', 'bg-primary', 'bg-secondary');
                        dot.classList.add('bg-outline');
                        this.lastChild.textContent = ' ' + name + ' [OFF]';
                    }
                });
            });

            // Filtro rápido de tabla
            const filterInput = document.getElementById('incident-filter');
            const tableBody = document.getElementById('incidents-body');
            if (filterInput && tableBody) {
                filterInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    const rows = tableBody.querySelectorAll('tr');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    });
                });
            }

            // Timer de incidente activo (incrementa cada segundo)
            let seconds = 872;
            const timerEl = document.getElementById('incident-timer');
            setInterval(() => {
                seconds++;
                const hrs = String(Math.floor(seconds / 3600)).padStart(2, '0');
                const mins = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                if (timerEl) {
                    timerEl.textContent = `T+ ${hrs}:${mins}:${secs}`;
                }
            }, 1000);

            // Simulación de fluctuaciones de telemetría en tiempo real
            const gridTemp = document.getElementById('grid-temp');
            const bannerTemp = document.getElementById('banner-temp');
            const gridSmoke = document.getElementById('grid-smoke');
            const gridHum = document.getElementById('grid-hum');
            const gridCore = document.getElementById('grid-core');
            const gridFlow = document.getElementById('grid-flow');
            const gridVolt = document.getElementById('grid-volt');

            setInterval(() => {
                if (gridTemp) {
                    const base = 42.8;
                    const delta = (Math.random() * 0.4 - 0.2).toFixed(1);
                    const val = (base + parseFloat(delta)).toFixed(1);
                    gridTemp.textContent = val;
                    if (bannerTemp) bannerTemp.textContent = val + '°C';
                }
                if (gridSmoke) {
                    const delta = Math.floor(Math.random() * 8 - 4);
                    gridSmoke.textContent = 485 + delta;
                }
                if (gridHum) {
                    const delta = (Math.random() * 0.6 - 0.3).toFixed(1);
                    gridHum.textContent = (68.4 + parseFloat(delta)).toFixed(1);
                }
                if (gridCore) {
                    const delta = (Math.random() * 0.2 - 0.1).toFixed(1);
                    gridCore.textContent = (31.2 + parseFloat(delta)).toFixed(1);
                }
                if (gridFlow) {
                    const delta = (Math.random() * 0.4 - 0.2).toFixed(1);
                    gridFlow.textContent = (18.5 + parseFloat(delta)).toFixed(1);
                }
                if (gridVolt) {
                    const delta = (Math.random() * 0.6 - 0.3).toFixed(1);
                    gridVolt.textContent = (228.4 + parseFloat(delta)).toFixed(1);
                }
            }, 1800);
        })();

        // Control de relés
        function toggleRelay(type, btn) {
            const statusEl = document.getElementById(`status-${type}`);
            if (!statusEl) return;
            if (statusEl.textContent.includes('ACTIVA') || statusEl.textContent.includes('100%') || statusEl.textContent.includes('ABIERTA')) {
                statusEl.textContent = 'APAGADO';
                statusEl.className = 'font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-surface-container text-outline font-bold font-mono';
            } else {
                if (type === 'sirena') {
                    statusEl.textContent = 'ACTIVA';
                    statusEl.className = 'font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-error text-surface-container-lowest font-bold font-mono';
                } else if (type === 'extractor') {
                    statusEl.textContent = '100% PWM';
                    statusEl.className = 'font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-secondary text-surface-container-lowest font-bold font-mono';
                } else if (type === 'valvula') {
                    statusEl.textContent = 'ABIERTA';
                    statusEl.className = 'font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-tertiary text-on-tertiary font-bold font-mono';
                }
            }
        }

        function triggerEStop(btn) {
            const status = document.getElementById('status-estop');
            if (confirm('¿CONFIRMAR PARADA DE EMERGENCIA TÉRMICA (E-STOP)?')) {
                status.textContent = 'DISPARADO (LOCK)';
                status.className = 'font-code-xs text-code-xs px-1.5 py-0.5 rounded bg-error text-surface-container-lowest font-bold font-mono animate-pulse';
                btn.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        function filterSeverity(val) {
            const rows = document.querySelectorAll('#incidents-body tr');
            rows.forEach(r => {
                if (val === 'ALL') {
                    r.style.display = '';
                } else {
                    r.style.display = r.innerText.includes(val) ? '' : 'none';
                }
            });
        }
    </script>
</x-app-layout>
