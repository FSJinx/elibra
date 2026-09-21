interface ItemCategory {
  id: number
  name: string
  item_type_id: number
  created_at: string
  updated_at: string
}

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

export const useItemCategoriesStore = defineStore('item_categories', {
  state: () => ({
    categories: [] as ItemCategory[],
    currentItemCategory: null as ItemCategory | null,
    loading: false,
    params: { ...defaultParams } as ItemCategoryParams,
  }),

  getters: {
    byItemType() {
      return (item_id: any) => this.categories.filter((i) => item_id === i.item_type_id)
    },
  },

  actions: {
    setItemCategories(data: ItemCategory[]) {
      this.categories = data
    },

    setCurrentItemCategory(data: ItemCategory | null) {
      this.currentItemCategory = data
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

      if (!forced && this.categories.length) return this.categories

      this.setLoading(true)

      try {
        const response = await get('item_type_category', { params: { ...this.params } })
        const data = response.data

        this.setItemCategories(data)
        return data
      } catch (error) {
        console.error('Error fetching item categories:', error)
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
