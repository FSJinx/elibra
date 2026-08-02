const librarianRoutes = [
  {
    path: '/librarian',
    meta: { requiresAuth: true, role: 'librarian' },
    redirect: { name: 'librarian' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),
    children: [
      {
        path: '',
        name: 'librarian',
        component: () => import('@/app/librarian/dashboard/Dashboard.vue'),
      },
      {
        path: 'academics',
        name: 'librarian.academics',
        component: () => import('@/app/librarian/academics/Academics.vue'),
      },
    ],
  },
]

export default librarianRoutes
