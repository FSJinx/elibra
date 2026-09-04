interface ItemCategory {
  id: number
  name: string
  item_type_id: number
  created_at: string 
  updated_at: string
}

export const itemCategoriesStore = defineStore('itemCategories', () => {
  const itemCategories = ref<ItemCategory[]>([])
  const currentItemCategory = ref<ItemCategory | null>(null)
  const loading = ref<boolean>(false)

  function setItemCategories(data: ItemCategory[]) {
    itemCategories.value = data
  }

  function setCurrentItemCategory(data: ItemCategory | null) {
    currentItemCategory.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  return {
    itemCategories,
    currentItemCategory,
    loading,

    setItemCategories,
    setCurrentItemCategory,
    setLoading,
  }
})