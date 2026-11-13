export const patronRoutes = [
  {
    path: '/patron',
    name: 'patron',
    meta: {
      breadcrumb: 'Patron',
      requiresAuth: true,
      role: 'patron',
    },
    redirect: { name: 'patron.dashboard' },
    component: () => import('@/layouts/patron/PatronLayout.vue'),
    children: [
      {
        path: '',
        name: 'patron.dashboard',
        component: () => import('@/app/patron/dashboard/Dashboard.vue'),
      },
      {
        path: 'digital-manuscript-submission',
        meta: { title: 'AcaRepo' },
        children: [
          {
            path: '',
            name: 'patron.acarepo',
            component: () => import('@/app/patron/acarepo/AcaRepo.vue'),
          },
          {
            path: 'form',
            name: 'patron.acarepo.form',
            meta: { requiresFlow: true },
            component: () => import('@/app/patron/acarepo/AcaRepoForm.vue'),
            beforeEnter: (from: any) => {
              if (!from.name) return false
            },
          },
        ],
      },
      {
        path: 'profile',
        name: 'patron.profile',
        component: () => import('@/app/patron/profile/Profile.vue'),
      },
      {
        path: 'services',
        name: 'patron.services',
        meta: { title: 'Library Services' },
        component: () => import('@/app/patron/services/Services.vue'),
      },
      {
        path: 'history',
        meta: { title: 'History' },
        children: [
          {
            path: '',
            name: 'patron.history',
            component: () => import('@/app/patron/history/History.vue'),
          },
          {
            path: 'borrowing',
            name: 'patron.borrowing',
            component: () => import('@/app/patron/history/borrowing/Borrowing.vue'),
          },
          {
            path: 'reservation',
            name: 'patron.reservation',
            component: () => import('@/app/patron/history/reservation/Reservation.vue'),
          },
          {
            path: 'attendance',
            name: 'patron.attendance',
            component: () => import('@/app/patron/history/attendance/Attendance.vue'),
          },
        ],
      },
    ],
  },
]
