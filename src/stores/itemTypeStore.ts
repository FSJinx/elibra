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

export const useItemTypeStore = defineStore('item_type', () => {
  const item_types = ref<ItemType[] | null>(null)
  const currentItemType = ref<ItemType | null>(null)
  const loading = ref<boolean>(false)
  const params = reactive<ItemTypeParams>({ ...defaultParams })

  function setItemTypes(data: ItemType[] | null) {
    item_types.value = data
  }

  function setCurrentItemType(data: ItemType | null) {
    currentItemType.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  async function fetch(forced = false) {
    if (!forced && item_types.value?.length) return item_types.value

    setLoading(true)

    try {
      const response = await get('item_types', { params: { ...params } })
      const data = response.data

      setItemTypes(data)
      return data
    } catch (error) {
      console.error('Error fetching item types:', error)
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
    item_types,
    currentItemType,
    loading,
    params,

    setItemTypes,
    setCurrentItemType,
    setLoading,
    fetch,
    refresh,
  }
})
