export const librarianCirculation = [
  {
    path: 'issue',
    meta: { breadcrumb: 'Issue', title: 'Circulation' },
    children: circulationIssue,
  },
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
