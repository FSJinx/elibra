export const librarianCirculation = [
  {
    path: 'loans',
    meta: { breadcrumb: 'Loans', title: 'Circulation' },
    children: ciruclationLoans,
  },
  {
    path: 'attendance',
    name: 'librarian.circulation.attendance',
    meta: { breadcrumb: 'Attendnace', title: 'Circulation' },
    component: () => import('@/app/librarian/circulation/attendance/Attendance.vue'),
  },
]
