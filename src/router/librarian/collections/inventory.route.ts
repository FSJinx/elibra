export const collectionsInventory = [
  {
    path: '',
    name: 'librarian.collections.inventory',
    component: () => import('@/app/librarian/collections/inventory/Inventory.vue'),
  },
  {
    path: ':id',
    name: 'librarian.collections.inventory.view',
    meta: { title: 'Inventory' },
    component: () => import('@/app/librarian/collections/inventory/ViewInventory.vue'),
    beforeEnter: async (to: any, from: any) => {
      const inventory = inventoryStore()
      const pop = usePopup()
      const crumb = useBreadcrumbStore()

      if (!inventory.currentData) {
        pop.load()
        try {
          const res = await inventory.show(to.params?.id)
          crumb.set(`${to.name}:${to.params.id}`, res.data?.inventory_code as string)
          pop.unload()
          return true
        } catch (e: any) {
          pop.error(e.response?.data?.message)
          return from.name ? false : router.back()
        }
      }
    },
  },
]
