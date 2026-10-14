const url = 'users'

export const useAdminUserStore = defineStore('admin_users', {
  state: () => ({
    data: null as User[] | null,
    currentData: null as User | null,
    loading: false as boolean,
    pop: usePopup(),
  }),

  getters: {},

  actions: {
    setData(data: User[]) {
      this.data = data
    },

    async fetch(forced = false) {
      if (this.data && !forced) return

      try {
        this.loading = true
        const res = await get(url)
        this.setData(res.data)
        return res
      } catch (e: any) {
        throw e
      } finally {
        this.loading = false
      }
    },
  },
})
