// Registro de Service Worker para PWA y Notificaciones Push en dispositivos móviles
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').then((reg) => {
            console.info('[AetherSens PWA] Service Worker activo en:', reg.scope);
        }).catch((err) => {
            console.warn('[AetherSens PWA] Fallo al registrar Service Worker:', err);
        });
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    if (document.querySelector('#historical-evolution-chart')) {
        const { default: ApexCharts } = await import('apexcharts');
        window.ApexCharts = ApexCharts;
    }

    if (document.querySelector('[data-dashboard-root]')) {
        const { initDashboard } = await import('./dashboard');
        initDashboard();
    }

    if (document.querySelector('[data-settings-root]')) {
        const { default: mqtt } = await import('mqtt');
        window.mqtt = mqtt;
    }
});
