export const ciruclationLoans = [
  {
    path: '',
    component: () => import('@/app/librarian/circulation/loans/Loans.vue'),
    children: [
      {
        path: '',
        name: 'librarian.circulation.loans',
        component: () => import('@/app/librarian/circulation/loans/issue/Recent.vue'),
      },
      {
        path: 'reservation',
        name: 'librarian.circulation.loans.reservation',
        component: () => import('@/app/librarian/circulation/loans/reservations/Reservations.vue'),
      },
    ],
  },
]
