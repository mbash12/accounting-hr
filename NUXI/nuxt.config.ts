// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    target: "static",
    ssr: false,

    nitro: {
        routeRules: {
            "/**": {
                headers: {
                    "Cross-Origin-Opener-Policy": "same-origin-allow-popups",
                },
            },
        },
    },

    app: {
        baseURL: "/",
        buildAssetsDir: "/_nuxt/",

        head: {
            title: "Elevate Attendance",
            link: [
                { rel: "icon", type: "image/x-icon", href: "/icon.png" },
            ],
        },
    },

    vite: {
        define: {
            "process.env.BASE_URL": JSON.stringify("/"),
        },
        build: {
            assetsDir: "_nuxt",
            rollupOptions: {
                output: {
                    assetFileNames: ({ name }) => {
                        if (/\.(gif|jpe?g|png|svg)$/.test(name ?? "")) {
                            return "assets/images/[name]-[hash][extname]";
                        }
                        return "_nuxt/[name]-[hash][extname]";
                    },
                    chunkFileNames: "_nuxt/[name]-[hash].js",
                    entryFileNames: "_nuxt/[name]-[hash].js",
                },
            },
        },
    },

    devtools: { enabled: false },
    pages: true,

    modules: [
        "nuxt-windicss",
        "@vueuse/nuxt",
        "@vite-pwa/nuxt",
        "nuxt-vuefire",
    ],

    plugins: ["~/plugins/sweetalert2.js"],

    vuefire: {
        auth: {
            enabled: true,
        },
        config: {
            apiKey: "AIzaSyBS_67f9veXrAsSYnFszPPX0dgae6Bvl6o",
            authDomain: "absensi-cde0a.firebaseapp.com",
            projectId: "absensi-cde0a",
            storageBucket: "absensi-cde0a.appspot.com",
            messagingSenderId: "672855281448",
            appId: "1:672855281448:web:001beb135cc2c1d4404a1d",
        },
    },

    pwa: {
        registerType: "autoUpdate",
        devOptions: {
            enabled: false,
            type: "module",
        },
        client: {
            installPrompt: true,
            periodicSyncForUpdates: 20

        },
        injectRegister: "auto",
        workbox: {
            globPatterns: ["**/*.{js,css,html,ico,png,svg}"],
            importScripts: ["firebase-messaging-sw.js"],
            navigateFallback: "/",
            cleanupOutdatedCaches: true,
            runtimeCaching: [
              {
                urlPattern: /^https:\/\/permithub\.pelangiservice\.com\/.*/i,
                handler: 'NetworkFirst',
                options: {
                  cacheName: 'api-cache',
                  expiration: {
                    maxEntries: 10,
                    maxAgeSeconds: 60 * 60 * 24 // 24 hours
                  },
                  cacheableResponse: {
                    statuses: [0, 200]
                  }
                }
              }
            ]
        },
        includeAssets: ["favicon.ico", "apple-touch-icon.png", "mask-icon.svg"],
        filename: "manifest.json",
        manifest: {
            id: "elevate-attendance",
            theme_color: "#40B6F4",
            background_color: "#ffffff",
            display: "standalone",
            scope: "/",
            start_url: "/",
            name: "Elevate Attendance",
            short_name: "Elevate",
            description: "Aplikasi Elevate Attendance",
            screenshots: [
                {
                    src: "screenshot.png",
                    sizes: "1024x1024",
                    type: "image/png",
                    form_factor: "wide",
                    label: "Elevate Attendance",
                },
                {
                    src: "screenshot.png",
                    sizes: "1024x1024",
                    type: "image/png",
                    form_factor: "narrow",
                    label: "Elevate Attendance",
                },
            ],
            icons: [
                {
                    src: "pwa-64x64.png",
                    sizes: "64x64",
                    type: "image/png",
                },
                {
                    src: "pwa-192x192.png",
                    sizes: "192x192",
                    type: "image/png",
                },
                {
                    src: "pwa-512x512.png",
                    sizes: "512x512",
                    type: "image/png",
                },
                {
                    src: "maskable-icon-512x512.png",
                    sizes: "512x512",
                    type: "image/png",
                    purpose: "maskable",
                },
            ],
        },
    },

    compatibilityDate: "2024-09-27",
});
