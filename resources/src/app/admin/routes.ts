export const adminRoutes = [
  {
    path: '',
    name: 'Admin',
    component: () => import('@/app/admin/dashboard/Dashboard.vue'),
  },
  {
    path: 'subscriptions',
    name: 'Admin Subscriptions',
    component: () => import('@/app/admin/subscriptions/Subscriptions.vue'),
  },
]
