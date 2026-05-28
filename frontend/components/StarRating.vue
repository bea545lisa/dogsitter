<template>
  <div class="flex items-center gap-1">
    <span v-for="i in 5" :key="i"
      :class="i <= Math.round(rating) ? 'text-yellow-400' : 'text-gray-300'">★</span>
    <span v-if="rating > 0" class="text-gray-500 ml-1">({{ rating.toFixed(1) }})</span>
    <span v-else class="text-gray-400 ml-1 text-xs">Noch keine Bewertung</span>
  </div>
</template>

<script setup lang="ts">
defineProps<{ rating: number }>()
</script>
