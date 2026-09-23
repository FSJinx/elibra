interface ItemType {
  id: number
  slug: string
  name: string
  created_at: string
  updated_at: string
}

interface ItemTypeParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<ItemTypeParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const paramsWatchers = new WeakSet<object>()

export const useItemTypeStore = defineStore('item_type', {
  state: () => ({
    item_types: null as ItemType[] | null,
    currentItemType: null as ItemType | null,
    loading: false,
    params: { ...defaultParams } as ItemTypeParams,
  }),

  getters: {
    byId() {
      return (id: any) => this.item_types?.find((i) => i.id === id)
    },
  },

  actions: {
    setItemTypes(data: ItemType[] | null) {
      this.item_types = data
    },

    setCurrentItemType(data: ItemType | null) {
      this.currentItemType = data
    },

    setLoading(status: boolean) {
      this.loading = status
    },

    async fetch(forced = false) {
      if (!paramsWatchers.has(this)) {
        paramsWatchers.add(this)
        watchDebounced(
          () => ({ ...this.params }),
          () => this.fetch(true),
        )
      }

      if (!forced && this.item_types?.length) return this.item_types

      this.setLoading(true)

      try {
        const response = await get('item_types', { params: { ...this.params } })
        const data = response.data

        this.setItemTypes(data)
        return data
      } catch (error) {
        console.error('Error fetching item types:', error)
        return []
      } finally {
        this.setLoading(false)
      }
    },

    async refresh() {
      Object.assign(this.params, defaultParams)
      return this.fetch(true)
    },
  },
})
