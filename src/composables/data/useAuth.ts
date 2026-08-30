export function useAuth() {
  const store = authStore()
  // const swal = useSwal()
  const pop = usePopup()

  // ========== ACTIONS ===========
  // ---------- COMPUTED ROUTER LINK THAT RETUNS THE PATH TO USER'S HOME ----------
  const userHomeLink = computed(() => {
    const routes: Record<string, string> = {
      admin: 'admin',
      librarian: 'librarian',
      patron: 'patron',
      default: 'home',
    }

    return routes[(store.user?.role as string) || 'default']
  })

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
      const data = response.data?.data.user

      if (!token) {
        throw new Error('Login succeeded but no token was returned.')
      }

      store.setToken(token)
      store.setUser(data)

      return { success: true, data: response?.data, reroute: userHomeLink.value }
    } catch (error: any) {
      console.log(error.response?.data);
      
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
              return router.push({ name: 'home' })
            })
          })

        console.log('Logged out successfully.')
      } catch (e) {
        throw e
      }
    }
  }

  return {
    userHomeLink: userHomeLink.value,
    getUser,
    login,
    logout,
  }
}
