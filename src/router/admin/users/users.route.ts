const pop = usePopup()
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
  {
    path: ':id',
    component: () => import('@/app/admin/users/ViewUser.vue'),
    beforeEnter: async (to: any) => {
      const user = userStore()
      pop.load()

      try {
        await user.show(to.params.id)
      } catch {
        return false
      } finally {
        pop.unload()
      }
    },
    children: [
      {
        path: '',
        meta: { breadcrumb: 'Profile' },
        name: 'admin.users.view',
        component: () => import('@/app/admin/users/view/Profile.vue'),
      },
      {
        path: 'account',
        meta: { breadcrumb: 'Account' },
        name: 'admin.users.account',
        component: () => import('@/app/admin/users/view/Account.vue'),
      },
      {
        path: 'circulation',
        meta: { breadcrumb: 'Circulation' },
        name: 'admin.users.view.circulation',
        component: () => import('@/app/admin/users/view/Circulation.vue'),
      },
      {
        path: 'reservation',
        meta: { breadcrumb: 'Reservation' },
        name: 'admin.users.view.reservation',
        component: () => import('@/app/admin/users/view/Reservation.vue'),
      },
      {
        path: 'attendance',
        meta: { breadcrumb: 'Attendance' },
        name: 'admin.users.view.attendance',
        component: () => import('@/app/admin/users/view/Attendance.vue'),
      },
    ],
  },
]
