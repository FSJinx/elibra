const librarianRoutes = [
  {
    path: '/librarian',
    meta: { breadcrumb: 'Librarian', requiresAuth: true, role: 'librarian' },
    name: 'librarian',
    redirect: { name: 'librarian.dashboard' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),
    children: [
      {
        path: '',
        name: 'librarian.dashboard',
        meta: { title: 'Dashboard', breadcrumb: 'Dashboard' },
        component: () => import('@/app/librarian/dashboard/Dashboard.vue'),
      },
      {
        path: '',
        name: 'librarian.notifications',
        meta: { breadcrumb: 'Notifications' },
        component: () => import('@/app/librarian/notifications/Notifications.vue'),
      },
      {
        path: 'academics',
        meta: { breadcrumb: 'Dashboard' },
        name: 'librarian.academics',
        component: () => import('@/app/librarian/academics/Academics.vue'),
      },
    ],
  },
]

export default librarianRoutes
