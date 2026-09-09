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

export const useItemCategoriesStore = defineStore('item_categories', () => {
  const item_categories = ref<ItemCategory[]>([])
  const currentItemCategory = ref<ItemCategory | null>(null)
  const loading = ref<boolean>(false)
  const params = reactive<ItemCategoryParams>({ ...defaultParams })

  function setItemCategories(data: ItemCategory[]) {
    item_categories.value = data
  }

  function setCurrentItemCategory(data: ItemCategory | null) {
    currentItemCategory.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  async function fetch(forced = false) {
    if (!forced && item_categories.value.length) return item_categories.value

    setLoading(true)

    try {
      const response = await get('item_type_category', { params: { ...params } })
      const data = response.data

      setItemCategories(data)
      return data
    } catch (error) {
      console.error('Error fetching item categories:', error)
      return []
    } finally {
      setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return fetch(true)
  }

  watchDebounced(
    () => ({ ...params }),
    () => fetch(true),
  )

  return {
    item_categories,
    currentItemCategory,
    loading,
    params,

    setItemCategories,
    setCurrentItemCategory,
    setLoading,
    fetch,
    refresh,
  }
})
