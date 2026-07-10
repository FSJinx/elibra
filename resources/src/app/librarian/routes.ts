export const librarianRoutes = [
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
]
