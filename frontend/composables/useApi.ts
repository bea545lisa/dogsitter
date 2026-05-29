// useApi ist unser zentraler HTTP-Client.
// Alle API-Aufrufe laufen darüber — nie direkt fetch() schreiben.
// Vorteil: API-URL kommt aus der Config, Auth-Token wird automatisch mitgeschickt.

export const useApi = () => {
  const config = useRuntimeConfig()
  const token = useCookie('auth_token')

  const apiFetch = $fetch.create({
    baseURL: config.public.apiBase,
    onRequest({ options }) {
      // Token bei jedem Request frisch auslesen und als Header setzen
      if (token.value) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token.value}`,
        }
      }
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        token.value = null
        navigateTo('/auth/login')
      }
    },
  })

  return { apiFetch }
}
