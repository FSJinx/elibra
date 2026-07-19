export const authRoute = [
  {
    path: '/auth',
    redirect: { name: 'login' },
    children: [
      {
        // Login
        path: 'login',
        name: 'login',
        component: () => import('@/app/auth/login/Login.vue'),
      },
    ],
  },
]