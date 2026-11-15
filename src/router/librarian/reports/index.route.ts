export const librarianReport = [
  {
    path: 'reports',
    name: 'librarian.reports',
    meta: { breadcrumb: 'Reports & Analytics', title: 'Reports & Analytics' },
    children: [
      { path: 'circulation', name: 'librarian.reports.circulation', meta: { breadcrumb: 'Circulation' }, component: () => import('@/app/librarian/reports/circulation/Circulation.vue') },
      { path: 'collection', name: 'librarian.reports.collection', meta: { breadcrumb: 'Collection' }, component: () => import('@/app/librarian/reports/Reports.vue') },
      { path: 'overdue', name: 'librarian.reports.overdue', meta: { breadcrumb: 'Overdue' }, component: () => import('@/app/librarian/reports/Reports.vue') },
      { path: 'inventory', name: 'librarian.reports.inventory', meta: { breadcrumb: 'Inventory' }, component: () => import('@/app/librarian/reports/Reports.vue') },
      { path: 'patrons', name: 'librarian.reports.patrons', meta: { breadcrumb: 'Patrons' }, component: () => import('@/app/librarian/reports/Reports.vue') },
      { path: 'acquisitions', name: 'librarian.reports.acquisitions', meta: { breadcrumb: 'Acquisitions' }, component: () => import('@/app/librarian/reports/Reports.vue') },
    ],
  },
]
