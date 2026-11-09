export const userStore = defineStore('users', {
  state: () => ({
    data: null as User[] | null,
    currentData: null as User | null,

    url: 'user',
  }),

  actions: {
    async fetch(params?: Partial<UserParams>) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<User>) {
      const res = await post(this.url, params)
      return res
    },

    async approveRegistration(user: User) {
      return await post(`${this.url}/${user.uuid}/approve-registration`)
    },

    async rejectRegistration(user: User) {
      return await post(`${this.url}/${user.uuid}/reject-registration`)
    },

    async delete(user: User) {
      return await del(`${this.url}/${user.uuid}`)
    },
  },
})
