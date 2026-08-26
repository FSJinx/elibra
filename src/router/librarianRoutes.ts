const librarianRoutes = [
  {
    path: '/librarian',
    meta: { breadcrumb: 'Librarian', requiresAuth: true, role: 'librarian' },
    name: 'librarian',
    redirect: { name: 'librarian.dashboard' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'librarian.dashboard',
        meta: { title: 'Dashboard', breadcrumb: 'Dashboard' },
        redirect: { name: 'librarian.dashboard.overview' },
        children: [
          {
            path: 'overview',
            name: 'librarian.dashboard.overview',
            meta: { title: 'Overview', breadcrumb: 'Overview' },
            component: () => import('@/app/librarian/dashboard/overview/Overview.vue'),
          },
          {
            path: '',
            name: 'librarian.dashboard.notifications',
            meta: { breadcrumb: 'Notifications' },
            component: () => import('@/app/librarian/notifications/Notifications.vue'),
          },
        ],
      },
    ],
  },
]

export default librarianRoutes
