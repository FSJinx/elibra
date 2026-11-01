interface UserParams extends BaseParams {}

const defaultParams: Readonly<Partial<UserParams>> = {
  query: '',
  sort: 'last_name',
  page: 1,
  per_page: 10,
  order: 'asc',
}

export const usersStore = defineStore('users', {
  state: () => ({
    data: null as User[] | null,

    url: 'user',
    params: defaultParams as UserParams,
  }),

  getters: {
    getFullName() {
      return (user: User) => {
        if (!user) return ''

        const u = user
        return `${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''} ${u.last_name ?? ''}`
      }
    },
  },

  actions: {
    setData(data: User[]) {
      this.data = data
    },

    async fetch() {
      try {
        const res = await get(this.url, this.params)
        this.setData(res.data.data)
        return res.data
      } catch (e: any) {
        throw e
      }
    },

    async create (params: Partial<User>) {
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
    }
  },
})
