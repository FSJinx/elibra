export const librarianRoutes = [
  {
    path: '',
    name: 'Librarian',
    component: () => import('@/app/librarian/dashboard/Dashboard.vue'),
  },
  {
    path: 'academics',
    name: 'Librarian Academics',
    component: () => import('@/app/librarian/dashboard/Dashboard.vue'),
  },
]
