import api from '@/plugins/axios'
import router from '@/router'
import { defineStore } from 'pinia'

// User object shape
export interface User {
  id: number | string
  first_name: string
  last_name: string
  middle_initial?: string | null
  role: 'admin' | 'librarian' | 'patron' | null
  email?: string
  [key: string]: any
  tools: ["gendash", "collacademic"],
}

interface AuthState {
  token: string | null
  user: User | null
  isAuthenticated: boolean
}

export const authStore = defineStore('user', {
  state: (): AuthState => ({
    token: localStorage.getItem('token') || null,
    user: null,
    isAuthenticated: false,
  }),

  getters: {
    fullName: (state): string => {
      if (!state.user) return ''
      const user = state.user
      return `${user.first_name} ${user.middle_initial ? user.middle_initial + '.' : ''} ${user.last_name}`
    },

    formalName: (state): string => {
      if (!state.user) return ''
      const user = state.user
      return `${user.last_name}, ${user.first_name} ${user.middle_initial ? user.middle_initial + '.' : ''}`
    },

    initials: (state): string => {
      if (!state.user) return ''
      const user = state.user
      return `${user.first_name?.[0] || ''}${user.last_name?.[0] || ''}`.toUpperCase()
    },
  },

  actions: {
    setUser(data: User) {
      this.user = data
      this.isAuthenticated = true
    },

    async setToken(token: string) {
      this.token = token
      localStorage.setItem('token', this.token)
    },

    async getUser() {
      try {
        if (this.token) {
          const res = await api.get('auth')
          if (res?.data?.data) {
            this.setUser(res.data.data as User)
          }
        }
      } catch (e) {
        // optional: handle or log error
        console.error('Failed to fetch user', e)
      }
    },

    home() {
      const routes: Record<string, string> = {
        admin: 'Admin',
        librarian: 'Librarian',
        patron: 'Patron',
        default: 'Home',
      }

      const roleKey = (this.user?.role as string) || 'default'
      // console.log(routes[roleKey])
      return router.push({ name: routes[roleKey] })
    },

    async logout() {
      const confirmed = window.confirm('Are you sure you want to logout?')
      if (confirmed) {
        this.clearUser()
        await router.replace({ name: 'Home' })
      }
    },

    clearUser() {
      localStorage.clear()
      this.$reset()
    },
  },
})
