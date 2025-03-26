const CACHE_NAME = "couch-potato-cache-v1";
const urlsToCache = [
    "/",
    "/index.php",
    "/login.php",
    "/manifest.json",
    "/images/logo_1.png",
    "/css/Newyears.CSS",
    "/images/S2UB31723335663.png",
    "/images/97UKj1723335831.png"
];

// Install Service Worker
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log("Opened cache");
            return cache.addAll(urlsToCache);
        })
    );
    // Force the waiting service worker to become the active service worker
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    // Claim any clients immediately
    self.clients.claim();
});

// Fetch Event
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            // Return cached version or fetch new
            return response || fetch(event.request).then((response) => {
                // Cache new responses for static assets
                if (response && response.status === 200) {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return response;
            });
        })
    );
});
