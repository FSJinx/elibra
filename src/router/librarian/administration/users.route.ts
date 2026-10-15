export const administrationUsers = [
  {
    path: '',
    name: 'librarian.users',
    component: () => import('@/app/librarian/administration/users/Users.vue'),
  },
  {
    path: 'new',
    name: 'librarian.users.new',
    component: () => import('@/app/librarian/administration/users/NewUser.vue'),
  },
]
