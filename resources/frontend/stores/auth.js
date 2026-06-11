import api from '@/plugins/axios'
import elpop from '@/plugins/elpop'
import router from '@/router'
import { defineStore } from 'pinia'

export const authStore = defineStore('user', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: null,
  }),

  getters: {
    fullName: (state) => {
      if (!state.user) return ''

      const user = state.user
      return `${user.first_name} ${user.middle_initial ? user.middle_initial + '.' : ''} ${user.last_name}`
    },

    formalName: (state) => {
      if (!state.user) return ''

      const user = state.user
      return `${user.last_name},  ${user.first_name} ${user.middle_initial ? user.middle_initial + '.' : ''}`
    },

    initials: (state) => {
      if (!state.user) return ''

      const user = state.user
      return `${user.first_name?.[0] || ''}${user.last_name?.[0] || ''}`.toUpperCase()
    },
  },

  actions: {
    setUser(data) {
      this.user = data
    },

    async setToken(token) {
      this.token = token
      localStorage.setItem('token', this.token)
    },

    async getUser() {
      elpop.load('Fetching user info...')

      try {
        if (this.token) {
          const res = await api.get('auth')
          if (res?.data) {
            this.setUser(res.data?.data)
          }
        }
      } finally {
        if (elpop.state.type === 'load') {
          elpop.close()
        }
      }
    },

    home() {
      let routes = {
        admin: 'Admin',
        librarian: 'Librarian',
        patron: 'Patron',
        null: 'Home',
      }

      router.push({ name: routes[this.user?.role || null] })
    },
    
    clearUser() {
      localStorage.clear()
      this.$reset()
    },
  },
})
