<template>
  <div class="max-w-2xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8">Mein Sitter-Profil</h1>

    <form @submit.prevent="submit" class="space-y-6">

      <div>
        <label class="block text-sm font-medium mb-1">Über mich</label>
        <textarea v-model="form.bio" rows="4" placeholder="Beschreibe dich und dein Angebot..."
          class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Stadt</label>
          <input v-model="form.city" type="text" required
            class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">PLZ</label>
          <input v-model="form.zip" type="text"
            class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" @click="geocode" :disabled="geocoding || !form.city"
          class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm disabled:opacity-50 transition">
          {{ geocoding ? 'Suche Koordinaten…' : '📍 Koordinaten automatisch ermitteln' }}
        </button>
        <span v-if="form.lat && form.lng" class="text-sm text-green-600">
          ✓ {{ form.lat }}, {{ form.lng }}
        </span>
        <span v-if="geocodeError" class="text-sm text-red-500">{{ geocodeError }}</span>
      </div>

      <div>
        <label class="block text-sm font-medium mb-2">Betreuungstyp</label>
        <div class="grid grid-cols-2 gap-3">
          <label :class="['border rounded-xl p-3 cursor-pointer text-center transition',
            form.care_type === 'private' ? 'border-primary-500 bg-primary-50' : 'border-gray-200']">
            <input v-model="form.care_type" type="radio" value="private" class="sr-only" />
            🏠 Privat
          </label>
          <label :class="['border rounded-xl p-3 cursor-pointer text-center transition',
            form.care_type === 'pension' ? 'border-primary-500 bg-primary-50' : 'border-gray-200']">
            <input v-model="form.care_type" type="radio" value="pension" class="sr-only" />
            🐾 Pension
          </label>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Preis halber Tag (€)</label>
          <input v-model="form.price_halfday" type="number" step="0.01" min="0"
            class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Preis ganzer Tag (€)</label>
          <input v-model="form.price_fullday" type="number" step="0.01" min="0"
            class="w-full border rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-2">Hundegrößen</label>
        <div class="flex gap-3">
          <label v-for="size in dogSizes" :key="size.value"
            :class="['border rounded-xl px-4 py-2 cursor-pointer transition',
              form.dog_sizes.includes(size.value) ? 'border-primary-500 bg-primary-50' : 'border-gray-200']">
            <input type="checkbox" :value="size.value" v-model="form.dog_sizes" class="sr-only" />
            {{ size.label }}
          </label>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <input v-model="form.is_active" type="checkbox" id="is_active" class="w-4 h-4 accent-primary-600" />
        <label for="is_active" class="text-sm">Profil aktiv (in Suche sichtbar)</label>
      </div>

      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <p v-if="success" class="text-green-600 text-sm">Profil gespeichert!</p>

      <button type="submit" :disabled="loading"
        class="w-full bg-primary-600 text-white py-2 rounded-xl hover:bg-primary-700 disabled:opacity-50">
        {{ loading ? 'Wird gespeichert…' : (isNew ? 'Profil anlegen' : 'Profil speichern') }}
      </button>

    </form>
  </div>
</template>

<script setup lang="ts">
const { apiFetch } = useApi()
const { user, fetchMe } = useAuth()

await fetchMe()

// Nur Sitter dürfen diese Seite sehen
if (user.value?.role !== 'sitter') {
  await navigateTo('/dashboard')
}

// Geocoding: Adresse → lat/lng über OpenStreetMap Nominatim (kostenlos, kein API-Key)
const geocoding = ref(false)
const geocodeError = ref<string | null>(null)

async function geocode() {
  geocoding.value = true
  geocodeError.value = null
  try {
    const query = [form.zip, form.city].filter(Boolean).join(' ')
    const results = await $fetch<any[]>('https://nominatim.openstreetmap.org/search', {
      query: { q: query, format: 'json', limit: 1, countrycodes: 'de' },
      headers: { 'Accept-Language': 'de' },
    })
    if (results.length === 0) {
      geocodeError.value = 'Ort nicht gefunden. Bitte Schreibweise prüfen.'
      return
    }
    form.lat = parseFloat(results[0].lat)
    form.lng = parseFloat(results[0].lon)
  } catch {
    geocodeError.value = 'Fehler bei der Koordinatensuche.'
  } finally {
    geocoding.value = false
  }
}

const dogSizes = [
  { value: 'small', label: 'Klein' },
  { value: 'medium', label: 'Mittel' },
  { value: 'large', label: 'Groß' },
]

const isNew = ref(true)
const loading = ref(false)
const error = ref<string | null>(null)
const success = ref(false)

const form = reactive({
  bio: '',
  city: '',
  zip: '',
  lat: '',
  lng: '',
  care_type: 'private' as 'private' | 'pension',
  price_halfday: '',
  price_fullday: '',
  dog_sizes: [] as string[],
  is_active: true,
})

// Vorhandenes Profil laden falls schon angelegt
try {
  const profile = await apiFetch<any>('/sitter/profile')
  if (profile) {
    isNew.value = false
    Object.assign(form, profile)
  }
} catch {}

async function submit() {
  loading.value = true
  error.value = null
  success.value = false
  try {
    if (isNew.value) {
      await apiFetch('/sitter/profile', { method: 'POST', body: form })
      isNew.value = false
    } else {
      await apiFetch('/sitter/profile', { method: 'PUT', body: form })
    }
    success.value = true
    setTimeout(() => navigateTo('/dashboard'), 1000)
  } catch {
    error.value = 'Profil konnte nicht gespeichert werden.'
  } finally {
    loading.value = false
  }
}
</script>
