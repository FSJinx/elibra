export const adminOverviewRoutes = [
  {
    path: 'overview',
    name: 'admin.overview',
    meta: {
      breadcrumb: 'Overview',
      permission: '',
      maintenance: false,
    },
    component: () => import('@/app/admin/dashboard/Dashboard.vue'),
  },
]
