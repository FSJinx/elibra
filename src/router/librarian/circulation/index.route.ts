export const librarianCirculation = [
  {
    path: '',
    meta: { title: 'Circulation' },
    children: [
      {
        path: 'loans',
        meta: { breadcrumb: 'Loans' },
        children: ciruclationLoans,
      },
      {
        path: 'holdings',
        meta: { breadcrumb: 'Holdings' },
        children: circulationHoldings,
      },
      {
        path: 'attendance',
        name: 'librarian.circulation.attendance',
        meta: { breadcrumb: 'Attendnace' },
        component: () => import('@/app/librarian/circulation/attendance/Attendance.vue'),
      },
    ],
  },
]
