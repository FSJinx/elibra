export const inventoryLineStore = defineStore('inventory_lines', {
  state: () => ({
    data: null as InventoryLine[] | null,
    currentData: null as InventoryLine | null,

    url: 'inventory-line',
  }),

  actions: {
    async fetch(params: Partial<InventoryLineParams>) {
      const res = await get(`${this.url}`, params)
      this.data = res.data.data
      return res
    },

    async verify(payload: UpdateInventoryLine) {
      const res = await put(`${this.url}/${payload.id}/verify`, payload)
      return res
    },
  },
})
