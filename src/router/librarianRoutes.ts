const librarianRoutes = [
  {
    path: '/librarian',
    meta: { breadcrumb: 'Librarian', requiresAuth: true, role: 'librarian' },
    name: 'librarian',
    redirect: { name: 'librarian.dashboard' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),
    children: [
      // ======== DASHBOARD ROUTES =========
      {
        path: 'dashboard',
        name: 'librarian.dashboard',
        meta: { breadcrumb: 'Dashboard' },
        redirect: { name: 'librarian.dashboard.overview' },
        children: [
          {
            path: 'overview',
            name: 'librarian.dashboard.overview',
            meta: { title: 'Overview', description: "Here is your library's performance overview for today.", breadcrumb: 'Overview' },
            component: () => import('@/app/librarian/dashboard/overview/Overview.vue'),
          },
          {
            path: '',
            name: 'librarian.dashboard.notifications',
            meta: { title: 'Notifications', breadcrumb: 'Notifications' },
            component: () => import('@/app/librarian/dashboard/notifications/Notifications.vue'),
          },
        ],
      },

      // ======== CATALOGING ROUTES =========
      {
        path: 'cataloging',
        name: 'librarian.cataloging',
        meta: { title: 'Cataloging', breadcrumb: 'Cataloging' },
        redirect: { name: 'librarian.dashboard.overview' },
        children: [
          {
            path: 'catalog',
            name: 'librarian.cataloging.catalog',
            meta: { title: 'Catalog', breadcrumb: 'Catalog' },
            component: () => import('@/app/librarian/cataloging/catalog/Catalog.vue'),
          },
        ],
      },
    ],
  },
]

export default librarianRoutes
