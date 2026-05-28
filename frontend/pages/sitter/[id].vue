<template>
  <div class="max-w-3xl mx-auto px-4 py-10">
    <div v-if="pending" class="text-center text-gray-500">Lade Profil…</div>

    <div v-else-if="sitter">
      <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-2xl font-bold text-primary-600">
          {{ sitter.user.name.charAt(0) }}
        </div>
        <div>
          <h1 class="text-2xl font-bold">{{ sitter.user.name }}</h1>
          <p class="text-gray-500">{{ sitter.city }}</p>
        </div>
        <StarRating :rating="sitter.average_rating" class="ml-auto" />
      </div>

      <p class="text-gray-700 mb-8">{{ sitter.bio }}</p>

      <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-gray-50 rounded-lg p-4">
          <p class="text-sm text-gray-500">Halber Tag</p>
          <p class="text-xl font-semibold">{{ sitter.price_halfday ? `${sitter.price_halfday} €` : '–' }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
          <p class="text-sm text-gray-500">Ganzer Tag</p>
          <p class="text-xl font-semibold">{{ sitter.price_fullday ? `${sitter.price_fullday} €` : '–' }}</p>
        </div>
      </div>

      <BookingForm :sitter-id="sitter.user_id" />

      <ReviewList :reviews="reviews ?? []" class="mt-12" />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { SitterProfile } from '~/composables/useSearch'

const route = useRoute()
const { apiFetch } = useApi()

// useFetch ist Nuxt-spezifisch: macht den Request SSR-fähig (wird auf dem Server ausgeführt)
// Das ist der Hauptvorteil gegenüber normalem fetch() — Google sieht den Inhalt
const { data: sitter, pending } = await useFetch<SitterProfile>(
  `/sitters/${route.params.id}`,
  { baseURL: useRuntimeConfig().public.apiBase }
)

const { data: reviews } = await useFetch(
  `/sitters/${route.params.id}/reviews`,
  { baseURL: useRuntimeConfig().public.apiBase }
)

// SEO: Seitentitel und Meta-Tags dynamisch setzen
useSeoMeta({
  title: () => sitter.value ? `${sitter.value.user.name} – Hundesitter in ${sitter.value.city}` : 'Hundesitter',
  description: () => sitter.value?.bio ?? '',
})
</script>
