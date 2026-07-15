export const adminRoutes = [
  {
    path: '/admin',
    meta: { requiresAuth: true, role: 'admin' },
    redirect: { name: 'admin' },
    component: () => import('@/layouts/management/Layout.vue'),
    children: [
      {
        path: '',
        name: 'admin',
        component: () => import('@/app/admin/dashboard/Dashboard.vue'),
      },
      {
        // Manage Subscriptions
        path: 'subscriptions',
        name: 'admin.subscriptions',
        component: () => import('@/app/admin/subscriptions/Subscriptions.vue'),
      },
      {
        // Campus List
        path: 'campus',
        name: 'admin.campus',
        meta: { title: 'Campus Management' },
        component: () => import('@/app/admin/campus/Campus.vue'),
      },
      {
        // Campus Details with List of Branches
        path: 'campus/id=:id',
        name: 'admin.campus.details',
        component: () => import('@/app/admin/campus/details/Details.vue'),
      },
    ],
  },
]
