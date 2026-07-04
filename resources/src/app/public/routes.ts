export const publicRoute = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/app/public/landing/LandingPage.vue'),
  },

  {
    path: '/login',
    name: 'Login',
    component: () => import('@/app/auth/login/Login.vue'),
  },

  {
    path: '/opac',
    name: 'Opac',
    component: () => import('@/app/public/opac/OpacPage.vue'),
  },
]
