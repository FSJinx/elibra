export const librarianReport = [
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
      }
]