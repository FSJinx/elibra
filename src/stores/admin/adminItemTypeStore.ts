export interface AdminItemType {
  id: any
  name: string
  slug: string
  loanable: boolean
  created_at: string
  updated_at: string
}

const url = 'item_types'

export const useAdminItemTypeStore = defineStore('admin.item_type', {
  state: () => ({
    data: null as AdminItemType[] | null,
    currentData: null as AdminItemType | null,
    loading: false as boolean,
  }),

  getters: {},

  actions: {
    setData(data: AdminItemType[]) {
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
