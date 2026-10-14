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
