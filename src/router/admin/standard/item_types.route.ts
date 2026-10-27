// These are child routes of Item Type Routes
export const standardItemType = [
  {
    path: '',
    name: 'admin.item_types',
    component: () => import('@/app/admin/item_type/ItemType.vue'),
  },
]
