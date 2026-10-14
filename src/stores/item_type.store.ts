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
const url = 'item_types'

export const useItemTypeStore = defineStore('item_type', {
  state: () => ({
    data: null as ItemType[] | null,
    currentData: null as ItemType | null,
    loading: false,
    params: { ...defaultParams } as ItemTypeParams,
  }),

  getters: {
    byId() {
      return (id: any) => this.data?.find((i) => i.id === id)
    },

    select() {
      return () => this.data?.sort((a, b) => a.name.localeCompare(b.name))
    },
  },

  actions: {
    setItemTypes(data: ItemType[] | null) {
      this.data = data
    },

    setCurrentItemType(data: ItemType | null) {
      this.currentData = data
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

      if (!forced && this.data?.length) return this.data

      this.setLoading(true)

      try {
        const response = await get(url, { params: { ...this.params } })
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
