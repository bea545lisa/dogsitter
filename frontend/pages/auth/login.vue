<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="bg-white rounded-2xl shadow p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6">Anmelden</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">E-Mail</label>
          <input v-model="form.email" type="email" required
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Passwort</label>
          <input v-model="form.password" type="password" required
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

        <button type="submit" :disabled="loading"
          class="w-full bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700 disabled:opacity-50">
          {{ loading ? 'Bitte warten…' : 'Anmelden' }}
        </button>
      </form>

      <p class="mt-4 text-sm text-center text-gray-500">
        Noch kein Konto?
        <NuxtLink to="/auth/register" class="text-primary-600 hover:underline">Registrieren</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const { login } = useAuth()
const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref<string | null>(null)

async function submit() {
  loading.value = true
  error.value = null
  try {
    await login(form.email, form.password)
  } catch {
    error.value = 'E-Mail oder Passwort falsch.'
  } finally {
    loading.value = false
  }
}
</script>
