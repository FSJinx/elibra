export const librarianStaffRoute = [
  {
    path: '',
    name: 'librarian.staffs',
    meta: { breadcrumb: 'Staffs', title: 'Administration' },
    component: () => import('@/app/librarian/administration/staffs/Staff.vue'),
  },
]
