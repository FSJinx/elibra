export const ciruclationLoans = [
  {
    path: '',
    component: () => import('@/app/librarian/circulation/loans/Loans.vue'),
    children: [
      {
        path: '',
        name: 'librarian.circulation.loans',
        component: () => import('@/app/librarian/circulation/loans/issue/Issue.vue'),
      },
      {
        path: 'recent',
        name: 'librarian.circulation.loans.recent',
        component: () => import('@/app/librarian/circulation/loans/recent/Recent.vue'),
      },
    ],
  },
]
