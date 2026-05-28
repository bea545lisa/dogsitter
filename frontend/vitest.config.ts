import { defineVitestConfig } from '@nuxt/test-utils/config'

export default defineVitestConfig({
  test: {
    // happy-dom ist eine schnelle Browser-Simulation für Tests
    // Alternative wäre jsdom, aber happy-dom ist schneller
    environment: 'happy-dom',
    globals: true,
  },
})
