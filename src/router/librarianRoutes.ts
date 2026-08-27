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
        meta: { breadcrumb: 'Cataloging' },
        redirect: { name: 'librarian.cataloging.catalog' },
        children: [
          {
            path: 'catalog',
            meta: { breadcrumb: 'Catalog' },
            children: [
              {
                path: '',
                meta: { title: 'Catalog' },
                name: 'librarian.cataloging.catalog',
                component: () => import('@/app/librarian/cataloging/catalog/Catalog.vue'),
              },
              {
                path: 'add-new',
                meta: { breadcrumb: 'Add New' },
                name: 'librarian.cataloging.add-new',
                redirect: { name: 'librarian.cataloging.add-new.book' },
                component: () => import('@/app/librarian/cataloging/catalog/NewCatalogItem.vue'),
                children: [
                  {
                    path: 'book',
                    meta: { breadcrumb: 'Book' },
                    name: 'librarian.cataloging.add-new.book',
                    component: () => import('@/app/librarian/cataloging/catalog/catalog_forms/Book.vue'),
                  },
                  {
                    path: 'academics',
                    meta: { breadcrumb: 'Academics' },
                    name: 'librarian.cataloging.add-new.academics',
                    component: () => import('@/app/librarian/cataloging/catalog/catalog_forms/Academics.vue'),
                  },
                  {
                    path: 'serials',
                    meta: { breadcrumb: 'Serials' },
                    name: 'librarian.cataloging.add-new.serials',
                    component: () => import('@/app/librarian/cataloging/catalog/catalog_forms/Serials.vue'),
                  },
                ],
              },
            ],
          },
        ],
      },
    ],
  },
]

export default librarianRoutes
