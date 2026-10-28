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
            component: () => import('@/app/patron/acarepo/AcaRepoForm.vue'),
          }
        ]
      },
      {
        path: '',
        name: 'patron.dashboard',
        component: () => import('@/app/patron/dashboard/Dashboard.vue'),
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
    ],
  },
]
