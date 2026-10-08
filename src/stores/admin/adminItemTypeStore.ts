

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

    async create(params: Partial<ItemType>) {
      const res = await post(url, params)
      this.data?.push(res.data)
      return res
    },

    async update(params: ItemType) {
      const res = await put(`${url}/${params.id}`, params)
      if (this.data) {
        this.data = this.data.map((item) => item.id === res.data.id ? res.data : item)
      }
      return res
    },

    async remove(params: ItemType) {
      const res = await del(`${url}/${params.id}`)
      this.data = this.data?.filter((item) => item.id !== params.id) ?? null
      return res
    },
  },
})
