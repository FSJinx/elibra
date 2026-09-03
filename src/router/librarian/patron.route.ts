export const librarianPatron = [
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
]
