document.addEventListener('DOMContentLoaded', async () => {
    if (document.querySelector('#historical-evolution-chart')) {
        const { default: ApexCharts } = await import('apexcharts');
        window.ApexCharts = ApexCharts;
    }

    if (document.querySelector('[data-dashboard-root]')) {
        const { initDashboard } = await import('./dashboard');
        initDashboard();
    }
});
