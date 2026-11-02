export const librarianCirculation = [
  {
    path: '',
    meta: { title: 'Circulation' },
    children: [
      {
        path: 'borrow',
        meta: { breadcrumb: 'Borrow' },
        children: ciruclationBorrow,
      },
      {
        path: 'return',
        meta: { breadcrumb: 'Return' },
        children: ciruclationReturn,
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
