export function useAuth() {
  const store = authStore()
  // const swal = useSwal()
  const pop = usePopup()

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
    pop.load('While we fetch your information...')
    try {
      const res = await api.get('auth')

      store.setUser(res.data.data as User)
    } catch (e) {
      console.error('Failed to fetch user', e)
      store.clearUser()
    } finally {
      pop.unload()
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

      nextTick(async () => {
        await goHome()
      })

      return { success: true, data: response?.data }
    } catch (error: any) {
      return { success: false, data: error.response?.data ?? { message: error.message } }
    } finally {
      store.loading = false
    }
  }

  // ================ LOGOUT FUNCTION =================
  async function logout() {
    const result = await pop.confirm({
      title: 'Logout',
      text: 'Are you sure you want to logout?',
      showCancelButton: true,
    })

    if (result.isConfirmed) {
      pop.load()
      try {
        await api
          .post('auth/logout')
          .then((res) => {
            pop.fire({ title: 'Success', text: res.data.message, icon: 'success' })
          })
          .finally(() => {
            store.clearUser()
            nextTick(() => {
              goHome()
            })
          })

        console.log('Logged out successfully.')
      } catch (e) {
        throw e
      }
    }
  }

  return {
    goHome,
    getUser,
    login,
    logout,
  }
}
