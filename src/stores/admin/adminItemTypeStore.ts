

const url = 'item_types'

export const useAdminItemTypeStore = defineStore('admin.item_type', {
  state: () => ({
    data: null as ItemType[] | null,
    currentData: null as ItemType | null,
    loading: false as boolean,
  }),

  getters: {},

  actions: {
    setData(data: ItemType[]) {
      this.data = data
    },

    async fetch(forced = false) {
      if (this.data && !this.loading) return this.data

      this.loading = true
      try {
          const res = await get(url)
          this.setData(res.data)
          
          this.loading = false
          return res
        } catch (e: any) {
            throw e
        }
    },
  },
})
