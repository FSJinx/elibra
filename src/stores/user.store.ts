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
        return e.response
      }
    },

    async create (params: Partial<User>) {
      const res = await post(this.url, params)
      return res
    }
  },
})
