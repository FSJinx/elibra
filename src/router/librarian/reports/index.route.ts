export const librarianReport = [
  {
    path: 'reports',
    name: 'librarian.reports',
    meta: { breadcrumb: 'Reports & Analytics', title: 'Reports & Analytics' },
    children: [
      { path: 'circulation', name: 'librarian.reports.circulation', meta: { breadcrumb: 'Circulation' }, component: () => import('@/app/librarian/reports/circulation/Circulation.vue') },
      { path: 'users', name: 'librarian.reports.user', meta: { breadcrumb: 'Users' }, component: () => import('@/app/librarian/reports/users/Users.vue') },
      { path: 'attendance', name: 'librarian.reports.attendance', meta: { breadcrumb: 'Attendance', title: 'Attendance', reportType: 'attendance', icon: 'person-check' }, component: () => import('@/app/librarian/reports/GenericReport.vue') },
      { path: 'collection', name: 'librarian.reports.collection', meta: { breadcrumb: 'Collection', title: 'Collection', reportType: 'item', icon: 'journals' }, component: () => import('@/app/librarian/reports/GenericReport.vue') },
      { path: 'reservations', name: 'librarian.reports.reservation', meta: { breadcrumb: 'Reservations', title: 'Reservations', reportType: 'reservation', icon: 'bookmark-check' }, component: () => import('@/app/librarian/reports/GenericReport.vue') },
      { path: 'acquisitions', name: 'librarian.reports.acquisition', meta: { breadcrumb: 'Acquisitions', title: 'Acquisitions', reportType: 'acquisition', icon: 'receipt' }, component: () => import('@/app/librarian/reports/GenericReport.vue') },
    ],
  },
]
