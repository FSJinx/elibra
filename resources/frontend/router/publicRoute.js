const publicRoutes = [
  {
    path: '/app',
    redirect: { name: 'App' },
    component: () => import('@/pages/App.vue'),
    children: [
      {
        path: '',
        name: 'App',
        component: () => import('@/pages/app/MainApp.vue'),
        meta: { title: 'e-Libra', requiresFlow: false },
      },
      {
        path: 'opac',
        name: 'OPAC',
        component: () => import('@/pages/app/OPACPage.vue'),
        meta: { title: 'ISU OPAC', requiresFlow: false },
      },
    ],
  },
]

export default publicRoutes
