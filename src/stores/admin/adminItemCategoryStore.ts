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
      if (this.data && !forced) return this.data

      this.loading = true
      try {
        const res = await get(url)
        this.setData(res.data)
        return res
      } finally {
        this.loading = false
      }
    },

    async create(params: Partial<ItemCategory>) {
      const res = await post(url, params)
      this.data?.push(res.data)
      return res
    },

    async update(params: ItemCategory) {
      const res = await put(`${url}/${params.id}`, params)
      if (this.data) {
        this.data = this.data.map((category) => category.id === res.data.id ? res.data : category)
      }
      return res
    },

    async remove(params: ItemCategory) {
      const res = await del(`${url}/${params.id}`)
      this.data = this.data?.filter((category) => category.id !== params.id) ?? null
      return res
    },
  },
})
