const pop = usePopup()
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
  {
    path: ':id',
    component: () => import('@/app/librarian/administration/users/ViewUser.vue'),
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
        name: 'librarian.users.view',
        component: () => import('@/app/librarian/administration/users/view/Profile.vue'),
      },
      {
        path: 'account',
        meta: { breadcrumb: 'Account' },
        name: 'librarian.users.account',
        component: () => import('@/app/librarian/administration/users/view/Account.vue'),
      },
      {
        path: 'circulation',
        meta: { breadcrumb: 'Circulation' },
        name: 'librarian.users.view.circulation',
        component: () => import('@/app/librarian/administration/users/view/Circulation.vue'),
      },
      {
        path: 'reservation',
        meta: { breadcrumb: 'Reservation' },
        name: 'librarian.users.view.reservation',
        component: () => import('@/app/librarian/administration/users/view/Reservation.vue'),
      },
      {
        path: 'attendance',
        meta: { breadcrumb: 'Attendance' },
        name: 'librarian.users.view.attendance',
        component: () => import('@/app/librarian/administration/users/view/Attendance.vue'),
      },
    ],
  },
]
