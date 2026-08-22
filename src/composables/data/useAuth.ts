export function useAuth() {
  const store = authStore()
  const swal = useSwal()

  // ========== ACTIONS ===========
  // ---------- FUNCTION TO GO HOME ----------
  function goHome() {
    const routes: Record<string, string> = {
      admin: 'admin',
      librarian: 'librarian',
      patron: 'patron',
      default: 'home',
    }

    const roleKey = (store.user?.role as string) || 'default'
    return router.push({ name: routes[roleKey] })
  }

  // --------- GETS USER  ---------
  async function getUser(): Promise<void> {
    store.setLoading(true)
    try {
      const res = await api.get('auth')

      store.setUser(res.data.data as User)
    } catch (e) {
      console.error('Failed to fetch user', e)
      store.clearUser()
    } finally {
      store.setLoading(false)
    }
  }

  // ================ LOGIN FUNCTION =================
  async function login({ username = null, password = null }: { username?: string | null; password?: string | null } = {}) {
    store.loading = true

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

      await store.setToken(token)
      await getUser()
      await goHome()

      return { success: true, data: response?.data }
    } catch (error: any) {
      return { success: false, data: error.response?.data ?? { message: error.message } }
    } finally {
      store.loading = false
    }
  }

  // ================ LOGOUT FUNCTION =================
  async function logout() {
    const result = await swal.fire({
      title: 'Logout',
      text: 'Are you sure you want to logout?',
      icon: 'warning',
      showCancelButton: true,
    })

    if (result.isConfirmed) {
      store.clearUser()
      nextTick(() => {
        goHome()
      })

      console.log('Logged out successfully.')
    }
  }

  return {
    goHome,
    getUser,
    login,
    logout
  }
}
