if (!self.define) { const e = e => { "require" !== e && (e += ".js"); let r = Promise.resolve(); return s[e] || (r = new Promise((async r => { if ("document" in self) { const s = document.createElement("script");
                    s.src = e, document.head.appendChild(s), s.onload = r } else importScripts(e), r() }))), r.then((() => { if (!s[e]) throw new Error(`Module ${e} didn’t register its module`); return s[e] })) },
        r = (r, s) => { Promise.all(r.map(e)).then((e => s(1 === e.length ? e[0] : e))) },
        s = { require: Promise.resolve(r) };
    self.define = (r, t, i) => { s[r] || (s[r] = Promise.resolve().then((() => { let s = {}; const o = { uri: location.origin + r.slice(1) }; return Promise.all(t.map((r => { switch (r) {
                    case "exports":
                        return s;
                    case "module":
                        return o;
                    default:
                        return e(r) } }))).then((e => { const r = i(...e); return s.default || (s.default = r), s })) }))) } }
define("./sw.js", ["./workbox-97ee8b27"], (function(e) { "use strict";
    self.addEventListener("message", (e => { e.data && "SKIP_WAITING" === e.data.type && self.skipWaiting() })), e.precacheAndRoute([{ url: "d8c88ff267c3217504bb.worker.js.LICENSE.txt", revision: "03234dce5ee7e3f4fbb409a4d1ba65ae" }, { url: "js/app.js.LICENSE.txt", revision: "808bec4ccc702b2703da7a73866b46c5" }, { url: "robots.txt", revision: "735ab4f94fbcd57074377afca324c813" }], { ignoreURLParametersMatching: [/^utm_/, /^fbclid$/] }) }));
//# sourceMappingURL=sw.js.map