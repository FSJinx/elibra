export const adminAuthorityRoute = [
  {
    path: 'languages',
    name: 'admin.languages',
    meta: { breadcrumb: 'Languages', title: 'Authority Control' },
    component: () => import('@/app/admin/language/Language.vue'),
  },
  {
    path: 'publishers',
    name: 'admin.publishers',
    meta: { breadcrumb: 'Publihser', title: 'Authority Control' },
    component: () => import('@/app/admin/publisher/Publisher.vue'),
  },
]
