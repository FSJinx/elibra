export interface AdminItemCategory {
  id: any
  name: string
  code: string
  item_type_id: any
  created_at: string
  updated_at: string
  [key: string]: any
}

const url = 'item_type_category'

export const useAdminItemCategoryStore = defineStore('admin.item_category', {
  state: () => ({
    data: null as AdminItemCategory[] | null,
    currentData: null as AdminItemCategory | null,
    loading: false as boolean,
  }),

  getters: {},

  actions: {
    setData(data: AdminItemCategory[]) {
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
