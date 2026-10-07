interface ItemCategoryParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<ItemCategoryParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const paramsWatchers = new WeakSet<object>()
const url = 'item_type_category'

export const useItemCategoriesStore = defineStore('item_categories', {
  state: () => ({
    data: [] as ItemCategory[],
    currentData: null as ItemCategory | null,
    loading: false,
    params: { ...defaultParams } as ItemCategoryParams,
  }),

  getters: {
    byItemType() {
      return (item_id: any) => this.data.filter((i) => item_id === i.item_type_id)
    },

    select() {
      return (item_id: any) => {
        const categories = this.data.sort((a, b) => a.name.localeCompare(b.name)).filter((i) => item_id === i.item_type_id)
        return categories
      }
    },
  },

  actions: {
    setItemCategories(data: ItemCategory[]) {
      this.data = data
    },

    setCurrentItemCategory(data: ItemCategory | null) {
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

      if (!forced && this.data.length) return this.data

      this.setLoading(true)

      try {
        const response = await get(url, { params: { ...this.params } })
        const data = response.data

        this.setItemCategories(data)
        return data
      } catch (error) {
        console.error('Error fetching item data:', error)
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
