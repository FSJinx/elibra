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

    async show(uuid: string) {
      const res = await get(`${this.url}/${uuid}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<User>) {
      const res = await post(this.url, params)
      return res
    },

    async update(user: User, params: Partial<User>) {
      const res = await put(`${this.url}/${user.id}`, params)
      this.currentData = res.data?.data ?? res.data ?? this.currentData
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

    async generateReport(params: StoreReport) {
      return await get(`${this.url}/generateReport`, params)
    }
  },
})
