export const usersAccounts = [
  {
    path: '',
    name: 'admin.users',
    component: () => import('@/app/admin/users/Users.vue'),
  },
  {
    path: 'new',
    name: 'admin.users.new',
    component: () => import('@/app/admin/users/NewUser.vue'),
  },
]
