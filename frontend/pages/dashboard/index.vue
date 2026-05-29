<template>
  <div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-2">Willkommen, {{ user?.name }}!</h1>
    <p class="text-gray-500 mb-8">{{ user?.role === 'sitter' ? 'Hundesitter-Konto' : 'Hundebesitzer-Konto' }}</p>

    <!-- Sitter: Profil noch nicht angelegt -->
    <div v-if="user?.role === 'sitter' && !hasProfile" class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
      <p class="font-medium text-yellow-800">Profil noch nicht vollständig</p>
      <p class="text-yellow-700 text-sm mt-1">Lege dein Sitter-Profil an damit Hundebesitzer dich finden können.</p>
      <NuxtLink to="/dashboard/profile"
        class="inline-block mt-3 bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
        Profil anlegen
      </NuxtLink>
    </div>

    <!-- Sitter: Profil vorhanden -->
    <div v-if="user?.role === 'sitter' && hasProfile" class="bg-white rounded-2xl shadow p-6 mb-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Mein Profil</h2>
        <NuxtLink to="/dashboard/profile"
          class="text-sm text-primary-600 hover:underline">
          Bearbeiten
        </NuxtLink>
      </div>
    </div>

    <!-- Buchungen -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold mb-4">
        {{ user?.role === 'sitter' ? 'Eingehende Anfragen' : 'Meine Buchungen' }}
      </h2>
      <p v-if="bookings.length === 0" class="text-gray-400">Noch keine Buchungen vorhanden.</p>
      <div v-else class="space-y-3">
        <div v-for="booking in bookings" :key="booking.id"
          class="border rounded-xl p-4 flex items-center justify-between">
          <div>
            <p class="font-medium">{{ booking.dog_name }}</p>
            <p class="text-sm text-gray-500">
              {{ formatDate(booking.from_date) }} – {{ formatDate(booking.to_date) }}
            </p>
          </div>
          <span :class="statusClass(booking.status)" class="text-xs font-medium px-3 py-1 rounded-full">
            {{ statusLabel(booking.status) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { user, fetchMe } = useAuth()
const { apiFetch } = useApi()

await fetchMe()

const bookings = ref<any[]>([])
const hasProfile = ref(false)

try {
  bookings.value = await apiFetch<any[]>('/bookings')
} catch {}

if (user.value?.role === 'sitter') {
  try {
    const profile = await apiFetch('/sitter/profile')
    hasProfile.value = !!profile
  } catch {}
}

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('de-DE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function statusLabel(status: string) {
  return { pending: 'Ausstehend', confirmed: 'Bestätigt', rejected: 'Abgelehnt', cancelled: 'Storniert' }[status] ?? status
}

function statusClass(status: string) {
  return {
    pending:   'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-green-100 text-green-700',
    rejected:  'bg-red-100 text-red-700',
    cancelled: 'bg-gray-100 text-gray-500',
  }[status] ?? 'bg-gray-100 text-gray-500'
}
</script>
