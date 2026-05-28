<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="bg-white rounded-2xl shadow p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6">Registrieren</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Name</label>
          <input v-model="form.name" type="text" required
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">E-Mail</label>
          <input v-model="form.email" type="email" required
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Passwort</label>
          <input v-model="form.password" type="password" required minlength="8"
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Passwort bestätigen</label>
          <input v-model="form.password_confirmation" type="password" required
            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Ich bin…</label>
          <div class="grid grid-cols-2 gap-3">
            <label :class="['border rounded-lg p-3 cursor-pointer text-center transition',
              form.role === 'owner' ? 'border-primary-500 bg-primary-50' : 'border-gray-200']">
              <input v-model="form.role" type="radio" value="owner" class="sr-only" />
              🐕 Hundebesitzer
            </label>
            <label :class="['border rounded-lg p-3 cursor-pointer text-center transition',
              form.role === 'sitter' ? 'border-primary-500 bg-primary-50' : 'border-gray-200']">
              <input v-model="form.role" type="radio" value="sitter" class="sr-only" />
              🏠 Hundesitter
            </label>
          </div>
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

        <button type="submit" :disabled="loading"
          class="w-full bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700 disabled:opacity-50">
          {{ loading ? 'Bitte warten…' : 'Konto erstellen' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
const { register } = useAuth()
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'owner' as 'owner' | 'sitter',
})
const loading = ref(false)
const error = ref<string | null>(null)

async function submit() {
  loading.value = true
  error.value = null
  try {
    await register(form)
  } catch {
    error.value = 'Registrierung fehlgeschlagen. Bitte alle Felder prüfen.'
  } finally {
    loading.value = false
  }
}
</script>
