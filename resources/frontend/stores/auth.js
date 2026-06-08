import api from '@/plugins/axios'
import elpop from '@/plugins/elpop'
import router from '@/router'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    id: null,
    first_name: '',
    last_name: '',
    middle_initial: '',
    email: '',
    username: '',
    role: null,
    avatar: null,
    authenticated: false,

    // All
    campus: {},

    // Librarian
    librarian: {
      sub_roles: {},
    },
    branch: {},

    // Patron
    department: {},
    program: {},
  }),

  getters: {
    fullName: (state) => {
      return `${state.first_name} ${state.middle_initial ? state.middle_initial + '.' : ''} ${state.last_name}`
    },

    formalName: (state) => {
      return `${state.last_name},  ${state.first_name} ${state.middle_initial ? state.middle_initial + '.' : ''}`
    },

    initials: (state) => {
      return `${state.first_name?.[0] || ''}${state.last_name?.[0] || ''}`.toUpperCase()
    },
  },

  actions: {
    setUser(data) {
      this.id = data.user.id
      this.first_name = data.user.first_name
      this.last_name = data.user.last_name
      this.middle_initial = data.user.middle_initial
      this.email = data.user.email
      this.username = data.user.username
      this.role = data.user.role
      this.avatar = data.user.avatar
      this.authenticated = true

      this.campus = data.campus

      if (this.role === 'librarian') {
        this.librarian = data.user.librarian
        this.librarian.sub_roles = data.sub_roles
        this.branch = data.branch
      }
    },

    async setToken(token) {
      this.token = token
      localStorage.setItem('token', this.token)
      await this.getUser()
    },

    async refreshToken() {
      await api
        .get('refresh', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        })
        .then((res) => {
          if (res.token) {
            this.setToken(res.token)
            this.getUser()
          }
        })
        .catch((err) => {
          elpop.error('Session expired, please login again.')
          router.push('Home')
        })
    },

    async getUser() {
      elpop.load('Fetching user info...')

      if (this.token) {
        const res = await api.get('auth', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        })

        if (res && res.data) {
          if (res.data) {
            this.setUser(res.data)
          }
        }
      }

      elpop.close()
    },

    home() {
      let routes = {
        admin: 'Admin',
        librarian: 'Librarian',
        patron: 'Patron',
      }

      router.push({ name: routes[this.role || 'Home'] })
    },

    updateProfile(payload) {
      Object.assign(this, payload)
    },

    clearUser() {
      this.$reset()
    },
  },
})
