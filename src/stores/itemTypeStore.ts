interface ItemType {
  id: number
  name: string
  created_at: string
  updated_at: string
}
export const itemTypeStore = defineStore('itemType', () => {
    const itemTypes = ref<ItemType[] | null>(null)
    const currentItemType = ref<ItemType | null>(null)
    const loading = ref<boolean>(false)


    function setItemTypes(data: ItemType[] | null) {
        itemTypes.value = data
    }
    
    function setCurrentItemType(data: ItemType | null) {
        currentItemType.value = data
    }
    
    function setLoading(status: boolean) {
        loading.value = status
    } 
    return {
        itemTypes,
        currentItemType,
        loading,

        setItemTypes,
        setCurrentItemType,
        setLoading,
    }
})
