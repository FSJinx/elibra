export const adminStandardRoutes = [
  {
    path: 'item_types',
    name: 'admin.item_types',
    meta: { breadcrumb: 'Item Types' },
    component: () => import('@/app/admin/item_type/ItemType.vue'),
  },
  {
    path: 'item_categories',
    name: 'admin.item_categories',
    meta: { breadcrumb: 'Item Categories' },
    component: () => import('@/app/admin/item_categories/ItemCategories.vue'),
  },
]
