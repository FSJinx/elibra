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
          {
            path: 'tickets',
            name: 'librarian.dashboard.tickets',
            meta: { title: 'Tickets', breadcrumb: 'Tickets' },
            component: () => import('@/app/librarian/dashboard/tickets/Tickets.vue'),
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
              { path: '', meta: { title: 'Catalog' }, name: 'librarian.cataloging.catalog', component: () => import('@/app/librarian/cataloging/catalog/Catalog.vue') },
              {
                path: ':id',
                name: 'librarian.cataloging.catalog.view',
                component: () => import('@/app/librarian/cataloging/catalog/ViewCatalog.vue'),
                children: [
                  { path: '', meta: { breadcrumb: 'Overview' }, name: 'librarian.cataloging.catalog.view.overview', component: () => import('@/app/librarian/cataloging/catalog/view/Overview.vue') },
                  { path: 'authors', meta: { breadcrumb: 'Authors' }, name: 'librarian.cataloging.catalog.view.authors', component: () => import('@/app/librarian/cataloging/catalog/view/Authors.vue') },
                  { path: 'accession', meta: { breadcrumb: 'Accession' }, name: 'librarian.cataloging.catalog.view.accession', component: () => import('@/app/librarian/cataloging/catalog/view/Accession.vue') },
                  { path: 'acquisition-history', meta: { breadcrumb: 'Acquisition' }, name: 'librarian.cataloging.catalog.view.acquisition', component: () => import('@/app/librarian/cataloging/catalog/view/Acquisition.vue') },
                ],
              },
              {
                path: 'add-new',
                meta: { breadcrumb: 'Add New' },
                name: 'librarian.cataloging.add-new',
                redirect: { name: 'librarian.cataloging.add-new.book' },
                component: () => import('@/app/librarian/cataloging/catalog/AddCatalog.vue'),
                children: [
                  { path: 'book', meta: { breadcrumb: 'Book' }, name: 'librarian.cataloging.add-new.book', component: () => import('@/app/librarian/cataloging/catalog/forms/Book.vue') },
                  { path: 'academics', meta: { breadcrumb: 'Academics' }, name: 'librarian.cataloging.add-new.academics', component: () => import('@/app/librarian/cataloging/catalog/forms/Academics.vue') },
                  { path: 'serials', meta: { breadcrumb: 'Serials' }, name: 'librarian.cataloging.add-new.serials', component: () => import('@/app/librarian/cataloging/catalog/forms/Serials.vue') },
                ],
              },
            ],
          },
        ],
      },

      // ======== CIRCULATION ROUTES =========
      {
        path: 'circulation',
        name: 'librarian.circulation',
        meta: { breadcrumb: 'Circulation' },
        children: [
          { path: 'loans', name: 'librarian.circulation.loans', meta: { title: 'Check-Out / Loans', breadcrumb: 'Check-Out / Loans' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
          { path: 'returns', name: 'librarian.circulation.returns', meta: { title: 'Returns', breadcrumb: 'Returns' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
          { path: 'renewals', name: 'librarian.circulation.renewals', meta: { title: 'Renewals', breadcrumb: 'Renewals' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
          { path: 'holds', name: 'librarian.circulation.holds', meta: { title: 'Holds & Reservations', breadcrumb: 'Holds & Reservations' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
          { path: 'fines', name: 'librarian.circulation.fines', meta: { title: 'Fines & Penalties', breadcrumb: 'Fines & Penalties' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
          { path: 'attendance', name: 'librarian.circulation.attendance', meta: { title: 'Attendance', breadcrumb: 'Attendance' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
        ],
      },

      // ======== COLLECTION ROUTES =========
      {
        path: 'collections',
        name: 'librarian.collections',
        meta: { breadcrumb: 'Cataloging & Collections' },
        children: [
          { path: 'inventory', name: 'librarian.collections.inventory', meta: { title: 'Inventory', breadcrumb: 'Inventory' }, component: () => import('@/app/librarian/collections/Collections.vue') },
          { path: 'classification', name: 'librarian.collections.classification', meta: { title: 'Classification', breadcrumb: 'Classification' }, component: () => import('@/app/librarian/collections/Collections.vue') },
          { path: 'authority', name: 'librarian.collections.author', meta: { title: 'Authority Control', breadcrumb: 'Authority Control' }, component: () => import('@/app/librarian/collections/Collections.vue') },
        ],
      },

      // ======== PATRON ROUTES =========
      {
        path: 'patrons',
        name: 'librarian.patrons',
        meta: { breadcrumb: 'Patrons' },
        redirect: { name: 'librarian.patrons.list' },
        children: [
          { path: '', name: 'librarian.patrons.list', meta: { title: 'Patrons', breadcrumb: 'Patrons' }, component: () => import('@/app/librarian/patrons/Patrons.vue') },
          { path: 'groups', name: 'librarian.patron-groups', meta: { title: 'Patron Groups', breadcrumb: 'Patron Groups' }, component: () => import('@/app/librarian/patrons/Patrons.vue') },
          { path: 'activity', name: 'librarian.patron-activity', meta: { title: 'Patron Activity History', breadcrumb: 'Patron Activity History' }, component: () => import('@/app/librarian/patrons/Patrons.vue') },
          { path: 'borrowing-history', name: 'librarian.borrowing-history', meta: { title: 'Borrowing History', breadcrumb: 'Borrowing History' }, component: () => import('@/app/librarian/patrons/Patrons.vue') },
        ],
      },

      // ======== ACQUISITION ROUTES =========
      {
        path: 'acquisition',
        name: 'librarian.acquisition',
        meta: { breadcrumb: 'Acquisitions' },
        children: [
          { path: 'requests', name: 'librarian.acquisition.requests', meta: { title: 'Requests', breadcrumb: 'Requests' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
          { path: 'purchase-orders', name: 'librarian.acquisition.purchase-orders', meta: { title: 'Purchase Orders', breadcrumb: 'Purchase Orders' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
          { path: 'vendors', name: 'librarian.acquisition.vendors', meta: { title: 'Vendors', breadcrumb: 'Vendors' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
          { path: 'budget-funds', name: 'librarian.acquisition.budget-funds', meta: { title: 'Budget & Funds', breadcrumb: 'Budget & Funds' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
          { path: 'donations', name: 'librarian.acquisition.donations', meta: { title: 'Donations & Gifts', breadcrumb: 'Donations & Gifts' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
        ],
      },

      // ======== SERIAL ROUTES =========
      {
        path: 'serials',
        name: 'librarian.serials',
        meta: { breadcrumb: 'Serials' },
        children: [
          { path: 'subscriptions', name: 'librarian.serials.subscriptions', meta: { title: 'Subscriptions', breadcrumb: 'Subscriptions' }, component: () => import('@/app/librarian/serials/Serials.vue') },
          { path: 'issue-tracking', name: 'librarian.serials.issue-tracking', meta: { title: 'Issue Tracking', breadcrumb: 'Issue Tracking' }, component: () => import('@/app/librarian/serials/Serials.vue') },
          { path: 'cataloging', name: 'librarian.serials.cataloging', meta: { title: 'Serials Cataloging', breadcrumb: 'Serials Cataloging' }, component: () => import('@/app/librarian/serials/Serials.vue') },
        ],
      },

      // ======== REPORT ROUTES =========
      {
        path: 'reports',
        name: 'librarian.reports',
        meta: { breadcrumb: 'Reports & Analytics' },
        children: [
          { path: 'circulation', name: 'librarian.reports.circulation', meta: { title: 'Circulation', breadcrumb: 'Circulation' }, component: () => import('@/app/librarian/reports/Reports.vue') },
          { path: 'collection', name: 'librarian.reports.collection', meta: { title: 'Collection', breadcrumb: 'Collection' }, component: () => import('@/app/librarian/reports/Reports.vue') },
          { path: 'overdue', name: 'librarian.reports.overdue', meta: { title: 'Overdue', breadcrumb: 'Overdue' }, component: () => import('@/app/librarian/reports/Reports.vue') },
          { path: 'inventory', name: 'librarian.reports.inventory', meta: { title: 'Inventory', breadcrumb: 'Inventory' }, component: () => import('@/app/librarian/reports/Reports.vue') },
          { path: 'patrons', name: 'librarian.reports.patrons', meta: { title: 'Patrons', breadcrumb: 'Patrons' }, component: () => import('@/app/librarian/reports/Reports.vue') },
          { path: 'acquisitions', name: 'librarian.reports.acquisitions', meta: { title: 'Acquisitions', breadcrumb: 'Acquisitions' }, component: () => import('@/app/librarian/reports/Reports.vue') },
        ],
      },

      // ======== ADMINISTRATION ROUTES =========
      {
        path: 'administration',
        name: 'librarian.administration',
        meta: { breadcrumb: 'Administration' },
        children: [
          { path: 'staff', name: 'librarian.administration.staff', meta: { title: 'Librarians / Staff', breadcrumb: 'Librarians / Staff' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'roles', name: 'librarian.administration.roles', meta: { title: 'Roles & Permissions', breadcrumb: 'Roles & Permissions' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'policies', name: 'librarian.administration.policies', meta: { title: 'Library Policies', breadcrumb: 'Library Policies' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'branches', name: 'librarian.administration.branches', meta: { title: 'Library Branches', breadcrumb: 'Library Branches' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'barcode', name: 'librarian.administration.barcode', meta: { title: 'Barcode Generator', breadcrumb: 'Barcode Generator' }, component: () => import('@/app/librarian/administration/Administration.vue') },
        ],
      },

      // ======== SETTINGS ROUTES =========
      {
        path: 'settings',
        name: 'librarian.settings',
        meta: { breadcrumb: 'Settings' },
        children: [
          { path: 'account', name: 'librarian.settings.account', meta: { title: 'Account', breadcrumb: 'Account' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'profile', name: 'librarian.settings.profile', meta: { title: 'Profile', breadcrumb: 'Profile' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'preferences', name: 'librarian.settings.preferences', meta: { title: 'Preferences', breadcrumb: 'Preferences' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'system', name: 'librarian.settings.system', meta: { title: 'System Settings', breadcrumb: 'System Settings' }, component: () => import('@/app/librarian/settings/Settings.vue') },
        ],
      },
    ],
  },
]

export default librarianRoutes
