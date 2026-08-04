// Inilabas sa labas para maging shared/global state
const loading = ref<boolean>(false)
const errorMessage = ref<string | null>(null)

export function useAuth() {
  const auth = authStore()
  return {
    user: {
      ...auth.user,
      isAuthenticated: auth.isAuthenticated,
    },

    goHome() {
      const routes: Record<string, string> = {
        admin: 'admin',
        librarian: 'librarian',
        patron: 'Patron',
        default: 'home',
      }

      const roleKey = (auth.user?.role as string) || 'default'
      return router.push({ name: routes[roleKey] })
    },

    // ================ LOGIN FUNCTION =================
    async login({ username = null, password = null }: { username?: string | null; password?: string | null } = {}) {
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

        this.goHome()
        return { success: true, data: response?.data }
      } catch (error: any) {
        return { success: false, data: error.response?.data ?? { message: error.message } }
      } finally {
        loading.value = false
      }
    },
  }

  // return {
  //   // States
  //   user,
  //   loading,
  //   errorMessage,

  //   // Actions
  //   login,
  //   goHome,
  // }
}
