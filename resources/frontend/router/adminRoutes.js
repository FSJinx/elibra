const adminRoutes = [
  {
    path: '/admin',
    name: 'Admin',
    component: () => import('@/layouts/AdminLayout.vue'),
  },
]

export default adminRoutes
