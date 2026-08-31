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
    meta: { breadcrumb: 'OPAC' },
    redirect: { name: 'opac.home' },
    component: () => import('@/layouts/opac/OpacLayout.vue'),
    children: [
      {
        path: '',
        name: 'opac.home',
        component: () => import('@/app/opac/Opac.vue'),
      },
      {
        path: ':id',
        name: 'opac.view',
        meta: { breadcrumb: '' },
        component: () => import('@/app/opac/OpacView.vue'),
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
        name: 'OPAC-old',
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
