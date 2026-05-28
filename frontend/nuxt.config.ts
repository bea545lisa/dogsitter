export default defineNuxtConfig({
  compatibilityDate: '2025-01-01',
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxt/image',
  ],

  runtimeConfig: {
    public: {
      // API-URL: lokal zeigt auf Laravel, in Produktion auf die echte API
      apiBase: process.env.NUXT_PUBLIC_API_BASE ?? 'http://localhost:8000/api',
    },
  },

  // Nuxt läuft auf Port 3000, Laravel auf 8000 — kein Konflikt
  devServer: {
    port: 3000,
  },
})
