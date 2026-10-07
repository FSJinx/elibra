const url = 'item_type_category'

export const useAdminItemCategoryStore = defineStore('admin.item_category', {
  state: () => ({
    data: null as ItemCategory[] | null,
    currentData: null as ItemCategory | null,
    loading: false as boolean,
  }),

  getters: {},

  actions: {
    setData(data: ItemCategory[]) {
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
