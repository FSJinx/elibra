export const adminUserRoutes = [
  {
    path: 'users',
    name: 'admin.users',
    meta: {
      title: 'User Management',
      description: 'Manage users accross campuses.',
      permission: '',
      maintenance: false,
    },
    component: () => import('@/app/admin/users/Users.vue'),
  },
]
