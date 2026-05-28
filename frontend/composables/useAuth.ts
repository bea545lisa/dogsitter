// useAuth kapselt alles rund um Login/Logout/Registrierung.
// Composables in Nuxt sind wiederverwendbare Funktionen mit Zugriff auf den Nuxt-Kontext.
// Sie werden überall (Pages, andere Composables, Stores) importiert.

interface User {
  id: number
  name: string
  email: string
  role: 'owner' | 'sitter'
}

export const useAuth = () => {
  const { apiFetch } = useApi()
  const token = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 30 }) // 30 Tage
  const user = useState<User | null>('auth_user', () => null)

  const isLoggedIn = computed(() => !!token.value)
  const isSitter = computed(() => user.value?.role === 'sitter')

  async function login(email: string, password: string) {
    const data = await apiFetch<{ user: User; token: string }>('/login', {
      method: 'POST',
      body: { email, password },
    })
    token.value = data.token
    user.value = data.user
    await navigateTo('/dashboard')
  }

  async function register(payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
    role: 'owner' | 'sitter'
  }) {
    const data = await apiFetch<{ user: User; token: string }>('/register', {
      method: 'POST',
      body: payload,
    })
    token.value = data.token
    user.value = data.user
    await navigateTo('/dashboard')
  }

  async function logout() {
    await apiFetch('/logout', { method: 'POST' })
    token.value = null
    user.value = null
    await navigateTo('/')
  }

  async function fetchMe() {
    if (!token.value) return
    user.value = await apiFetch<User>('/me')
  }

  return { user, isLoggedIn, isSitter, login, register, logout, fetchMe }
}
