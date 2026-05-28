<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navigation -->
    <header class="bg-white shadow-sm">
      <nav class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
        <NuxtLink to="/" class="text-xl font-bold text-primary-600">🐾 Dogsitter</NuxtLink>

        <div class="flex items-center gap-4 text-sm">
          <template v-if="isLoggedIn">
            <NuxtLink to="/dashboard" class="text-gray-600 hover:text-primary-600">Dashboard</NuxtLink>
            <button @click="logout" class="text-gray-600 hover:text-primary-600">Abmelden</button>
          </template>
          <template v-else>
            <NuxtLink to="/auth/login" class="text-gray-600 hover:text-primary-600">Anmelden</NuxtLink>
            <NuxtLink to="/auth/register"
              class="bg-primary-600 text-white px-4 py-1.5 rounded-lg hover:bg-primary-700">
              Registrieren
            </NuxtLink>
          </template>
        </div>
      </nav>
    </header>

    <!-- Seiteninhalt wird hier eingefügt -->
    <main class="flex-1">
      <slot />
    </main>

    <footer class="bg-white border-t mt-auto py-6 text-center text-sm text-gray-400">
      © {{ new Date().getFullYear() }} Dogsitter – webwork-oberland.de
    </footer>
  </div>
</template>

<script setup lang="ts">
const { isLoggedIn, logout } = useAuth()
</script>
