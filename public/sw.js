// const CACHE_NAME = 'stampit-cache-v1';
// const OFFLINE_URL = '/offline.html';

// self.addEventListener('install', (event) => {
//   event.waitUntil((async () => {
//     const cache = await caches.open(CACHE_NAME);
//     await cache.addAll([
//       OFFLINE_URL,
//       '/',
//       '/favicon.ico',
//       '/favicon.svg',
//       '/apple-touch-icon.png',
//     ]);
//     self.skipWaiting();
//   })());
// });

// self.addEventListener('activate', (event) => {
//   event.waitUntil((async () => {
//     const keys = await caches.keys();
//     await Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)));
//     self.clients.claim();
//   })());
// });

// // Network-first for HTML; Cache-first for others
// self.addEventListener('fetch', (event) => {
//   const request = event.request;

//   if (request.method !== 'GET') {
//     return;
//   }

//   const isDocument = request.headers.get('accept')?.includes('text/html');

//   if (isDocument) {
//     event.respondWith((async () => {
//       try {
//         const networkResponse = await fetch(request);
//         const cache = await caches.open(CACHE_NAME);
//         cache.put(request, networkResponse.clone());
//         return networkResponse;
//       } catch (error) {
//         const cached = await caches.match(request);
//         return cached || caches.match(OFFLINE_URL);
//       }
//     })());
//   } else {
//     event.respondWith((async () => {
//       const cached = await caches.match(request);
//       if (cached) return cached;
//       try {
//         const networkResponse = await fetch(request);
//         const cache = await caches.open(CACHE_NAME);
//         cache.put(request, networkResponse.clone());
//         return networkResponse;
//       } catch (error) {
//         return cached || new Response(null, { status: 504 });
//       }
//     })());
//   }
// });


