export const inventoryStore = defineStore('inventory', {
  state: () => ({
    data: null as Inventory[] | null,
    currentData: null as Inventory | null,
    loading: false as true | false,

    url: 'inventory',
  }),

  getters: {},

  actions: {
    // =========== ACTIONS ============
    async fetch(params?: Partial<InventoryParams>) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreInventory) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: Inventory) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async destory(params: Inventory) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async restore(params: Inventory) {
      const res = await patch(`${this.url}/${params?.id}`)
      return res
    },
  },
})
