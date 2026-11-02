export const librarianCirculation = [
  {
    path: '',
    meta: { title: 'Circulation' },
    children: [
      {
        path: 'check-out',
        meta: { breadcrumb: 'Check Out' },
        children: circulationCheckOut,
      },
      {
        path: 'check-in',
        meta: { breadcrumb: 'Check In' },
        children: ciruclationCheckIn,
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
