export const patronRoutes = [
  {
    path: '/patron',
    name: 'patron',
    meta: {
      breadcrumb: 'Patron',
      requiresAuth: true,
      role: 'patron',
    },
    redirect: { name: 'patron.profile' },
    component: () => import('@/layouts/patron/PatronLayout.vue'),
    children: [
      {
        path: 'profile',
        name: 'patron.profile',
        component: () => import('@/app/patron/profile/Profile.vue'),
      },
    ],
  },
]
