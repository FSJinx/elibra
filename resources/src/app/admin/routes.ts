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
        path: 'subscriptions',
        name: 'admin.subscriptions',
        component: () => import('@/app/admin/subscriptions/Subscriptions.vue'),
      },
      {
        path: 'campus',
        name: 'admin.campus',
        meta: { title: 'Campus Management' },
        component: () => import('@/app/admin/campus/Campus.vue'),
      },
    ],
  },
]
