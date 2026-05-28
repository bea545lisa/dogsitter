<template>
  <div class="bg-gray-50 rounded-2xl p-6">
    <h2 class="text-lg font-semibold mb-4">Buchungsanfrage senden</h2>

    <div v-if="!isLoggedIn" class="text-center py-4">
      <p class="text-gray-500 mb-3">Du musst angemeldet sein um eine Anfrage zu senden.</p>
      <NuxtLink to="/auth/login"
        class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
        Jetzt anmelden
      </NuxtLink>
    </div>

    <form v-else @submit.prevent="submit" class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Von</label>
          <input v-model="form.from_date" type="date" required :min="today"
            class="w-full border rounded-lg px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Bis</label>
          <input v-model="form.to_date" type="date" required :min="form.from_date || today"
            class="w-full border rounded-lg px-3 py-2" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Name des Hundes</label>
          <input v-model="form.dog_name" type="text" required
            class="w-full border rounded-lg px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Größe</label>
          <select v-model="form.dog_size" required class="w-full border rounded-lg px-3 py-2">
            <option value="small">Klein (&lt; 10 kg)</option>
            <option value="medium">Mittel (10–25 kg)</option>
            <option value="large">Groß (&gt; 25 kg)</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Nachricht (optional)</label>
        <textarea v-model="form.message" rows="3"
          class="w-full border rounded-lg px-3 py-2" />
      </div>

      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <p v-if="success" class="text-green-600 text-sm">Anfrage gesendet! Der Sitter meldet sich bei dir.</p>

      <button type="submit" :disabled="loading"
        class="w-full bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700 disabled:opacity-50">
        {{ loading ? 'Wird gesendet…' : 'Anfrage senden' }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ sitterId: number }>()
const { apiFetch } = useApi()
const { isLoggedIn } = useAuth()

const today = new Date().toISOString().split('T')[0]
const form = reactive({
  sitter_id: props.sitterId,
  dog_name: '',
  dog_size: 'medium' as 'small' | 'medium' | 'large',
  from_date: '',
  to_date: '',
  message: '',
})
const loading = ref(false)
const error = ref<string | null>(null)
const success = ref(false)

async function submit() {
  loading.value = true
  error.value = null
  try {
    await apiFetch('/bookings', { method: 'POST', body: form })
    success.value = true
  } catch {
    error.value = 'Anfrage konnte nicht gesendet werden.'
  } finally {
    loading.value = false
  }
}
</script>
