<template>
  <main>
    <!-- Hero -->
    <section class="bg-primary-600 text-white py-20 px-4 text-center">
      <h1 class="text-4xl font-bold mb-4">Liebevolle Betreuung für deinen Hund</h1>
      <p class="text-lg mb-10 opacity-90">Finde zuverlässige Hundesitter in deiner Urlaubsregion</p>

      <SearchBar @search="onSearch" />
    </section>

    <!-- Suchergebnisse (erscheinen nach Suche) -->
    <section v-if="results.length > 0 || loading" class="max-w-5xl mx-auto px-4 py-12">
      <p v-if="loading" class="text-center text-gray-500">Suche läuft…</p>
      <p v-else class="text-gray-600 mb-6">{{ results.length }} Sitter gefunden</p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SitterCard v-for="sitter in results" :key="sitter.id" :sitter="sitter" />
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
const { results, loading, search } = useSearch()

async function onSearch(params: Parameters<typeof search>[0]) {
  await search(params)
  // Bei Ergebnissen auf der gleichen Seite nach unten scrollen
  nextTick(() => {
    document.querySelector('section:nth-child(2)')?.scrollIntoView({ behavior: 'smooth' })
  })
}
</script>
