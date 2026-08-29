const publicRoute = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/app/public/LandingPage.vue'),
  },

  {
    path: '/login',
    name: 'login',
    component: () => import('@/app/auth/login/Login.vue'),
  },

  {
    path: '/opac',
    name: 'opac',
    redirect: { name: 'opac.home' },
    component: () => import('@/layouts/opac/OpacLayout.vue'),
    children: [
      {
        path: '',
        name: 'opac.home',
        meta: { breadcrumb: 'OPAC' },
        component: () => import('@/app/opac/Opac.vue'),
      },
    ],
  },

  {
    path: '/library',
    children: [
      {
        path: '',
        name: 'Library',
        component: () => import('@/app/public/components/library/LibraryPage.vue'),
      },
      {
        path: 'collections',
        name: 'OPAC',
        component: () => import('@/app/public/components/library/OpacPage.vue'),
      },
      {
        path: 'sample',
        name: 'Sample',
        component: () => import('@/app/public/components/library/SamplePage.vue'),
      },
    ],
  },
]

export default publicRoute
