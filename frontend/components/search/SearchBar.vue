<template>
  <form @submit.prevent="submit" class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
    <input
      v-model="city"
      type="text"
      placeholder="Urlaubsort eingeben, z. B. München"
      required
      class="flex-1 px-4 py-3 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-white"
    />
    <button
      type="submit"
      class="bg-white text-primary-600 font-semibold px-6 py-3 rounded-xl hover:bg-primary-50 transition"
    >
      Suchen
    </button>
  </form>
</template>

<script setup lang="ts">
import type { SearchParams } from '~/composables/useSearch'

// defineEmits: teilt Nuxt/Vue mit, welche Events diese Komponente nach außen sendet.
// Der Parent (index.vue) hört mit @search="..." darauf.
const emit = defineEmits<{
  search: [params: SearchParams]
}>()

const city = ref('')

function submit() {
  if (!city.value.trim()) return
  emit('search', { city: city.value.trim() })
}
</script>
