const adminRoutes = [
  {
    path: '/a',
    name: 'Admin',
    redirect: { name: 'Admin Dashboard' },
    component: () => import('@/layouts/SystemLayout.vue'),
    meta: { title: 'Admin', requiresFlow: true, role: 'admin' },
    children: [
      {
        path: 'dashboard',
        name: 'Admin Dashboard',
        component: () => import('@/pages/admin/DashboardPage.vue'),
        meta: { title: 'Dashboard', requiresFlow: true, maintenance: false },
      },
      {
        path: 'campus',
        name: 'Admin Campus Management',
        component: () => import('@/pages/admin/CampusPage.vue'),
        meta: { title: 'Campus Management', requiresFlow: true, maintenance: false },
      },
    ],
  },
]

export default adminRoutes
