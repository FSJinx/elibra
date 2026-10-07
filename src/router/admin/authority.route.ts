export const adminAuthorityRoute = [
  {
    path: 'languages',
    name: 'admin.languages',
    meta: { breadcrumb: 'Languages', title: 'Authority Control' },
    component: () => import('@/app/admin/language/Language.vue'),
  },
]
