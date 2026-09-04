export const librarianCirculation = [
  {
    path: 'circulation',
    name: 'librarian.circulation',
    meta: { breadcrumb: 'Circulation' },
    children: [
      { path: 'loans', name: 'librarian.circulation.loans', meta: { title: 'Check-Out / Loans', breadcrumb: 'Check-Out / Loans' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
      { path: 'returns', name: 'librarian.circulation.returns', meta: { title: 'Returns', breadcrumb: 'Returns' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
      { path: 'renewals', name: 'librarian.circulation.renewals', meta: { title: 'Renewals', breadcrumb: 'Renewals' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
      { path: 'holds', name: 'librarian.circulation.holds', meta: { title: 'Holds & Reservations', breadcrumb: 'Holds & Reservations' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
      { path: 'fines', name: 'librarian.circulation.fines', meta: { title: 'Fines & Penalties', breadcrumb: 'Fines & Penalties' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
      { path: 'attendance', name: 'librarian.circulation.attendance', meta: { title: 'Attendance', breadcrumb: 'Attendance' }, component: () => import('@/app/librarian/circulation/Circulation.vue') },
    ],
  },
]
