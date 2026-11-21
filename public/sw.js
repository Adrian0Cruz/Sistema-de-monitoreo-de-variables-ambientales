// ══════════════════════════════════════════════════════════════════════════════
// AETHERSENS - SERVICE WORKER PWA & NOTIFICACIONES PUSH PARA MÓVILES
// ══════════════════════════════════════════════════════════════════════════════

const CACHE_NAME = 'aethersens-pwa-v1';

self.addEventListener('install', (event) => {
    // Activar inmediatamente el nuevo Service Worker sin esperar cierre de pestañas
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        clients.claim().then(() => {
            console.log('[AetherSens SW] Service Worker activo y reclamando clientes.');
        })
    );
});

// ── Recepción de eventos Push desde el servidor (Web Push Protocol) ──
self.addEventListener('push', (event) => {
    let data = {
        title: '⚠️ Alerta AetherSens',
        body: 'Nueva anomalía detectada en sala técnica.',
        icon: '/favicon.png',
        badge: '/favicon.png',
        tag: 'aethersens-active-alert',
        severity: 'warning'
    };

    if (event.data) {
        try {
            const json = event.data.json();
            data = Object.assign({}, data, json);
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const isCritical = data.severity === 'critical';
    const options = {
        body: data.body,
        icon: data.icon || '/favicon.png',
        badge: data.badge || '/favicon.png',
        tag: data.tag || 'aethersens-active-alert',
        vibrate: isCritical ? [300, 150, 300, 150, 300] : [200, 100, 200],
        requireInteraction: isCritical,
        data: {
            url: data.url || '/dashboard'
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// ── Recepción de mensajes locales desde dashboard.js para mostrar en móvil ──
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
        const { title, options } = event.data;
        self.registration.showNotification(title, options);
    }
});

// ── Al hacer clic en la notificación desde el móvil ──
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) || '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
