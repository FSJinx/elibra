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
  },
]
