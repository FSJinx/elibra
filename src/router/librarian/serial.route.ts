export const librarianSerial = [
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
]
