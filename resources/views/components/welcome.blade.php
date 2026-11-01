<div class="p-6 lg:p-8 bg-surface-container border-b border-outline-variant/30 rounded-xl">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-primary-container/20 border border-primary/30 flex items-center justify-center text-primary shadow-sm">
            <span class="material-symbols-outlined text-[28px]">sensors</span>
        </div>
        <div>
            <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface">
                AetherSens Enterprise
            </h1>
            <p class="font-body-sm text-body-sm text-outline">
                Supervisión Ambiental y Telemetría en Tiempo Real &bull; Centro de Datos
            </p>
        </div>
    </div>

    <p class="mt-6 font-body-md text-body-md text-on-surface-variant leading-relaxed">
        Plataforma interna de control ambiental en tiempo real para temperatura, humedad relativa, índice de calor y detección de humo en infraestructura crítica de servidores. Conforme a las directrices térmicas internacionales <strong>ASHRAE TC 9.9</strong> y adaptada al clima cálido de Montería.
    </p>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('dashboard') }}" class="p-5 rounded-lg bg-surface-container-high border border-outline-variant/30 hover:border-primary/50 transition-colors group">
            <div class="flex items-center gap-2 text-primary font-headline-sm text-headline-sm font-semibold mb-1">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <span>Panel de Control</span>
            </div>
            <p class="font-body-sm text-body-sm text-outline">Supervisión en vivo con gráfica continua de doble eje y alertas.</p>
        </a>

        <a href="{{ route('settings') }}" class="p-5 rounded-lg bg-surface-container-high border border-outline-variant/30 hover:border-secondary/50 transition-colors group">
            <div class="flex items-center gap-2 text-secondary font-headline-sm text-headline-sm font-semibold mb-1">
                <span class="material-symbols-outlined text-[20px]">tune</span>
                <span>Configuración</span>
            </div>
            <p class="font-body-sm text-body-sm text-outline">Calibración persistente de umbrales térmicos y enlace MQTT.</p>
        </a>

        <a href="{{ route('docs') }}" class="p-5 rounded-lg bg-surface-container-high border border-outline-variant/30 hover:border-tertiary/50 transition-colors group">
            <div class="flex items-center gap-2 text-tertiary font-headline-sm text-headline-sm font-semibold mb-1">
                <span class="material-symbols-outlined text-[20px]">menu_book</span>
                <span>Manuales &amp; Docs</span>
            </div>
            <p class="font-body-sm text-body-sm text-outline">Descarga de manuales de usuario y esquemas de conexionado ESP32.</p>
        </a>
    </div>
</div>
