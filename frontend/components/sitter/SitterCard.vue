<template>
  <!-- NuxtLink erzeugt einen <a>-Tag, der clientseitig navigiert (kein full-page-reload) -->
  <NuxtLink :to="`/sitter/${sitter.id}`"
    class="block bg-white rounded-2xl shadow hover:shadow-md transition p-5">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-600">
        {{ sitter.user.name.charAt(0) }}
      </div>
      <div>
        <p class="font-semibold">{{ sitter.user.name }}</p>
        <p class="text-sm text-gray-500">{{ sitter.city }}</p>
      </div>
      <StarRating :rating="sitter.average_rating" class="ml-auto text-sm" />
    </div>

    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ sitter.bio }}</p>

    <div class="flex justify-between text-sm">
      <span class="text-gray-500">
        {{ sitter.care_type === 'private' ? 'Privat' : 'Pension' }}
      </span>
      <span class="font-semibold text-primary-600">
        ab {{ sitter.price_halfday ?? sitter.price_fullday }} €
      </span>
    </div>
  </NuxtLink>
</template>

<script setup lang="ts">
import type { SitterProfile } from '~/composables/useSearch'

// defineProps: deklariert was diese Komponente von außen erwartet.
// TypeScript prüft damit, dass der Parent immer das richtige Objekt übergibt.
defineProps<{ sitter: SitterProfile }>()
</script>
