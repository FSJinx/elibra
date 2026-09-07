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
        component: () => import('@/app/librarian/circulation/loans/Loans.vue'),
        beforeEnter: (to: any, from: any) => {
          console.log(to, from)
        },
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
