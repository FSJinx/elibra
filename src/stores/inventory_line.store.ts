export const inventoryLineStore = defineStore('inventory_lines', {
  state: () => ({
    data: null as InventoryLine[] | null,
    currentData: null as InventoryLine | null,

    url: 'inventory-line'
  }),

  actions: {
    async fetch(params: Partial<InventoryLineParams>) {
        const res = await get(`${this.url}`, params)
        this.data = res.data.data
        return res
    }
  }
})
