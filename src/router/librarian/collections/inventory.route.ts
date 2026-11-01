export const collectionsInventory = [
  {
    path: '',
    name: 'librarian.collections.inventory',
    component: () => import('@/app/librarian/collections/inventory/Inventory.vue'),
  },
  {
    path: ':id',
    name: 'librarian.collections.inventory.view',
    component: () => import('@/app/librarian/collections/inventory/ViewInventory.vue'),
    beforeEnter: async (to: any) => {
      const inventory = inventoryStore()
      const pop = usePopup()

      pop.load()
      try {
        await inventory.show(to.params?.id)
        pop.unload()
        return true
      } catch (e: any) {
        pop.error(e.response?.data?.message)
        return false
      }
    },
  },
]
