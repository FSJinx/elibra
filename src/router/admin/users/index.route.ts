export const adminUsers = [
  {
    path: '',
    meta: { title: 'Users & Access' },
    children: [
      {
        path: 'users',
        meta: { breadcrumb: 'Users' },
        children: usersAccounts,
      },
    ],
  },
]
