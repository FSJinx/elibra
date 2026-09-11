export const librarianCirculation = [
  {
    path: 'circulation',
    name: 'librarian.circulation',
    redirect: { name: 'librarian.circulation.loans' },
    meta: { title: 'Circulation', breadcrumb: 'Circulation' },
    // component: () => import('@/app/librarian/circulation/Returns.vue'),
    children: [
      {
        path: 'loans',
        name: 'librarian.circulation.loans',
        meta: { breadcrumb: 'Loans' },
        redirect: {name: 'librarian.circulation.loans.active'},
        component: () => import('@/app/librarian/circulation/loans/Loans.vue'),
        children: [
          {
            path: 'active',
            name: 'librarian.circulation.loans.active',
            meta: {breadcrumb: 'Active'},
            component: () => import('@/app/librarian/circulation/loans/active/Active.vue')
          },
          {
            path: 'overdue',
            name: 'librarian.circulation.loans.overdue',
            meta: {breadcrumb: 'Overdue'},
            component: () => import('@/app/librarian/circulation/loans/overdue/Overdue.vue')
          },
          {
            path: 'history',
            name: 'librarian.circulation.loans.history',
            meta: {breadcrumb: 'History'},
            component: () => import('@/app/librarian/circulation/loans/history/History.vue')
          },
        ],
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
