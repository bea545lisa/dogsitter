<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Bewertungen</h2>
    <p v-if="reviews.length === 0" class="text-gray-500">Noch keine Bewertungen.</p>
    <div v-else class="space-y-4">
      <div v-for="review in reviews" :key="review.id" class="border rounded-xl p-4">
        <div class="flex items-center gap-3 mb-2">
          <span class="font-medium">{{ review.author.name }}</span>
          <StarRating :rating="review.rating" class="text-sm" />
        </div>
        <p class="text-gray-600 text-sm">{{ review.comment }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  reviews: { id: number; rating: number; comment: string; author: { name: string } }[]
}>()
</script>
