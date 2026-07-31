// Inilabas sa labas para maging shared/global state
const loading = ref<boolean>(false)
const errorMessage = ref<string | null>(null)

export function useAuth() {
  const auth = authStore()

  function goHome() {
    const routes: Record<string, string> = {
      admin: 'admin',
      librarian: 'librarian',
      patron: 'Patron',
      default: 'home',
    }

    console.log(auth.user?.role as string)

    const roleKey = (auth.user?.role as string) || 'default'
    return router.push({ name: routes[roleKey] })
  }

  function user() {
    return auth.user
  }

  async function login({ username = null, password = null }: { username?: string | null; password?: string | null } = {}) {
    loading.value = true
    errorMessage.value = null

    try {
      const response = await api.post('auth/login', {
        username,
        password,
      })

      // I-extract ang token batay sa structure ng API mo
      const token = response.data?.data?.token

      if (!token) {
        throw new Error('Login succeeded but no token was returned.')
      }

      // I-save ang token at i-fetch ang user details
      await auth.setToken(token)
      await auth.getUser()

      goHome()
      return { success: true }
    } catch (error: any) {
      // Kunin ang error message mula sa Axios response o fallback sa generic error
      const message = error.response?.data?.message || error.response?.data?.error || error.message || 'An unexpected error occurred.'

      errorMessage.value = message
      console.error('Login Error:', message)

      // Nagre-return ng status para madaling gamitin sa UI component
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  return {
    // States
    loading,
    errorMessage,

    // Actions
    login,
    goHome,
    user,
  }
}
