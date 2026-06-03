import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    id: 1,
    firstname: 'Administrator',
    lastname: '',
    middlename: '',
    email: '',
    role: 'admin',
    avatar: null,
    authenticated: false,
  }),

  getters: {
    fullname: (state) => {
      return [state.firstname, state.middlename, state.lastname].filter(Boolean).join(' ')
    },

    initials: (state) => {
      return `${state.firstname?.[0] || ''}${state.lastname?.[0] || ''}`.toUpperCase()
    },
  },

  actions: {
    setUser(user) {
      this.id = user.id
      this.firstname = user.firstname
      this.lastname = user.lastname
      this.middlename = user.middlename
      this.email = user.email
      this.role = user.role
      this.avatar = user.avatar
      this.authenticated = true
    },

    updateProfile(payload) {
      Object.assign(this, payload)
    },

    clearUser() {
      this.$reset()
    },
  },
})
