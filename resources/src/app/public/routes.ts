export const publicRoute = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/app/public/landing/LandingPage.vue'),
  },

  {
    path: '/login',
    name: 'login',
    component: () => import('@/app/auth/login/Login.vue'),
  },

  {
    path: '/library',
    children: [
      {
        path: '',
        name: 'Library',
        component: () => import('@/app/public/library/LibraryPage.vue'),
      },
      {
        path: 'collections',
        name: 'OPAC',
        component: () => import('@/app/public/library/OpacPage.vue'),
      },
      {
        path: 'sample',
        name: 'Sample',
        component: () => import('@/app/public/library/SamplePage.vue'),
      },
    ],
  },
]
