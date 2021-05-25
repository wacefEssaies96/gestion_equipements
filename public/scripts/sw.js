if (!self.define) {
    const e = e => {
            "require" !== e && (e += ".js");
            let r = Promise.resolve();
            return s[e] || (r = new Promise((async r => {
                if ("document" in self) {
                    const s = document.createElement("script");
                    s.src = e, document.head.appendChild(s), s.onload = r
                } else importScripts(e), r()
            }))), r.then((() => { if (!s[e]) throw new Error(`Module ${e} didn’t register its module`); return s[e] }))
        },
        r = (r, s) => { Promise.all(r.map(e)).then((e => s(1 === e.length ? e[0] : e))) },
        s = { require: Promise.resolve(r) };
    self.define = (r, t, i) => {
        s[r] || (s[r] = Promise.resolve().then((() => {
            let s = {};
            const n = { uri: location.origin + r.slice(1) };
            return Promise.all(t.map((r => {
                switch (r) {
                    case "exports":
                        return s;
                    case "module":
                        return n;
                    default:
                        return e(r)
                }
            }))).then((e => { const r = i(...e); return s.default || (s.default = r), s }))
        })))
    }
}
define("./sw.js", ["./workbox-97ee8b27"], (function(e) {
    "use strict";
    self.addEventListener("message", (e => { e.data && "SKIP_WAITING" === e.data.type && self.skipWaiting() })), e.precacheAndRoute([{ url: "manifest.json", revision: "4b1e8e02bd7060f7b79888f2541b2d9a" }, { url: "mix-manifest.json", revision: "0f4e2ee0f7e2ca9da665d2f8035743df" }], { ignoreURLParametersMatching: [/^utm_/, /^fbclid$/] })
}));

const CACHE_NAME = 'offline';
const OFFLINE_URL = '/';

self.addEventListener('install', function(event) {
    console.log('[ServiceWorker] Install');

    event.waitUntil((async() => {
        const cache = await caches.open(CACHE_NAME);
        // Setting {cache: 'reload'} in the new request will ensure that the response
        // isn't fulfilled from the HTTP cache; i.e., it will be from the network.
        await cache.add(new Request(OFFLINE_URL, { cache: 'reload' }));
    })());

    self.skipWaiting();
});
self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activate');
    event.waitUntil((async() => {
        // Enable navigation preload if it's supported.
        // See https://developers.google.com/web/updates/2017/02/navigation-preload
        if ('navigationPreload' in self.registration) {
            await self.registration.navigationPreload.enable();
        }
    })());

    // Tell the active service worker to take control of the page immediately.
    self.clients.claim();
});

self.addEventListener('fetch', function(event) {
    // console.log('[Service Worker] Fetch', event.request.url);
    if (event.request.mode === 'navigate') {
        event.respondWith((async() => {
            try {
                const preloadResponse = await event.preloadResponse;
                if (preloadResponse) {
                    return preloadResponse;
                }

                const networkResponse = await fetch(event.request);
                return networkResponse;
            } catch (error) {
                console.log('[Service Worker] Fetch failed; returning offline page instead.', error);

                const cache = await caches.open(CACHE_NAME);
                const cachedResponse = await cache.match(OFFLINE_URL);
                return cachedResponse;
            }
        })());
    }
});