export interface ItemParams extends BaseParams {
  item_type_id: any
  item_type_category_id: any
}

export const useItemStore = defineStore('item', {
  state: () => ({
    url: '/items',
  }),

  actions: {
    async create(params: Partial<Item>) {
      const res = await post(this.url, params)
      return res
    },

    async fetch(params: ItemParams) {
      const res = await get(this.url, params)
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      return res
    },

    /**
     *
     * @description Relational fetches
     *
     */

    async fetchResource(resource: string, id: any) {
      const res = await get(`${this.url}/${id}/${resource}`)
      return res
    },
  },
})
