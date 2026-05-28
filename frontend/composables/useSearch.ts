export interface SitterProfile {
  id: number
  user_id: number
  city: string
  zip: string
  lat: number
  lng: number
  bio: string
  care_type: 'private' | 'pension'
  price_halfday: number | null
  price_fullday: number | null
  dog_sizes: ('small' | 'medium' | 'large')[]
  average_rating: number
  user: { id: number; name: string }
}

export interface SearchParams {
  city?: string
  lat?: number
  lng?: number
  radius?: number
  dog_size?: 'small' | 'medium' | 'large'
  care_type?: 'private' | 'pension'
}

export const useSearch = () => {
  const { apiFetch } = useApi()
  const results = ref<SitterProfile[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function search(params: SearchParams) {
    loading.value = true
    error.value = null
    try {
      results.value = await apiFetch<SitterProfile[]>('/sitters', { query: params })
    } catch {
      error.value = 'Suche fehlgeschlagen. Bitte erneut versuchen.'
    } finally {
      loading.value = false
    }
  }

  return { results, loading, error, search }
}
