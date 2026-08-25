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
    // ============= STATES ===============
    const token = ref<string | null>(null)
    const user = ref<User | null>(null)
    const isAuthenticated = ref<boolean>(false)
    const loading = ref<boolean>(false)
    const swal = useSwal()

    // ============= SETTERS ===============
    const setUser = (data: User) => {
      user.value = data
      isAuthenticated.value = true
    }

    function setToken(newToken: string) {
      token.value = newToken
    }

    function setLoading(state: boolean) {
      loading.value = state
    }

    // ============= GETTERS ===============
    const getFullName = computed<string>(() => {
      if (!user.value) return ''

      const u = user.value
      return `${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''} ${u.last_name}`
    })
    
    const displayRole = computed(() => {
      if (!user.value)
      return 
    })

    const getFormalName = computed<string>(() => {
      if (!user.value) return ''

      const u = user.value
      return `${u.last_name}, ${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''}`
    })

    const getInitials = computed<string>(() => {
      if (!user.value) return ''

      const u = user.value
      return `${u.first_name?.[0] || ''}${u.last_name?.[0] || ''}`.toUpperCase()
    })

    // ============= ACTIONS ===============
    function clearUser() {
      token.value = null
        user.value = null
        isAuthenticated.value = false
    }

    return {
      // ============= STATE ===============
      token,
      user,
      isAuthenticated,
      loading,

      // ============= SETTERS ===============
      setUser,
      setToken,
      setLoading,

      // ============= GETTERS ===============
      getFullName,
      getFormalName,
      getInitials,

      // ============= ACTIONS ===============
      clearUser,
    }
  },
  {
    persist: {
      pick: ['token'],
    },
  },
)
