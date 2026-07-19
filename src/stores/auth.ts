// User object shape
export interface User {
  id: number | string
  first_name: string
  last_name: string
  middle_initial?: string | null
  role: 'admin' | 'librarian' | 'patron' | null
  email?: string
  [key: string]: any
  tools: ['gendash', 'collacademic']
}

export const authStore = defineStore(
  'user',
  () => {
    // --- STATE ---
    const token = ref<string | null>(null)
    const user = ref<User | null>(null)
    const isAuthenticated = ref<boolean>(false)

    // --- GETTERS ---
    const fullName = computed<string>(() => {
      if (!user.value) return ''
      const u = user.value
      return `${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''} ${u.last_name}`
    })

    const formalName = computed<string>(() => {
      if (!user.value) return ''
      const u = user.value
      return `${u.last_name}, ${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''}`
    })

    const initials = computed<string>(() => {
      if (!user.value) return ''
      const u = user.value
      return `${u.first_name?.[0] || ''}${u.last_name?.[0] || ''}`.toUpperCase()
    })

    // --- ACTIONS ---
    function setUser(data: User) {
      user.value = data
      isAuthenticated.value = true
    }

    function setToken(newToken: string) {
      token.value = newToken
    }

    async function getUser(): Promise<void> {
      try {
        if (token.value) {
          const res = await api.get('auth')
          if (res?.data?.data) {
            setUser(res.data.data as User)
          }
        }
      } catch (e) {
        console.error('Failed to fetch user', e)
        clearUser()
      }
    }

    function home() {
      const routes: Record<string, string> = {
        admin: 'admin',
        librarian: 'librarian',
        patron: 'Patron',
        default: 'home',
      }

      const roleKey = (user.value?.role as string) || 'default'
      return router.push({ name: routes[roleKey] })
    }

    async function logout(): Promise<void> {
      const confirmed = window.confirm('Are you sure you want to logout?')
      if (confirmed) {
        clearUser()
        await router.replace({ name: 'Home' })
      }
    }

    function clearUser() {
      token.value = null
      user.value = null
      isAuthenticated.value = false
    }

    // I-export lahat para magamit ng components
    return {
      token,
      user,
      isAuthenticated,
      fullName,
      formalName,
      initials,
      setUser,
      setToken,
      getUser,
      home,
      logout,
      clearUser,
    }
  },
  {
    // Dito sa Setup Store syntax, tinatanggap ng tama ng TypeScript ang config object na ito:
    persist: {
      pick: ['token'],
    },
  },
)
