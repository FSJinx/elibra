const librarianRoutes = [
  {
    path: '/l',
    name: 'Librarian',
    redirect: { name: 'Librarian Dashboard' },
    component: () => import('@/layouts/SystemLayout.vue'),
    meta: { title: 'Librarian', requiresFlow: true, role: 'librarian' },
    children: [
      {
        path: 'dashboard',
        name: 'Librarian Dashboard',
        component: () => import('@/pages/librarian/DashboardPage.vue'),
        meta: { title: 'Dashboard', requiresFlow: true, maintenance: false },
      },
      {
        path: 'library',
        name: 'Librarian User Management',
        component: () => import('@/pages/librarian/UserPage.vue'),
        meta: { title: 'User Management', requiresFlow: true, maintenance: false },
      },
    ],
  },
]

export default librarianRoutes
