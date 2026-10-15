export const librarianSectionRoute = [
  {
    path: '',
    name: 'librarian.sections',
    meta: { breadcrumb: 'Sections', title: 'Administration' },
    component: () => import('@/app/librarian/administration/sections/Sections.vue'),
  },
]
