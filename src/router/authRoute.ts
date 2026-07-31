export const authRoute = [
  {
    path: '/auth',
    redirect: { name: 'login' },
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      {
        // Login
        path: 'login',
        name: 'login',
        component: () => import('@/app/auth/login/Login.vue'),
      },
      {
        // Register
        path: 'register',
        name: 'register',
        component: () => import('@/app/auth/register/Register.vue'),
      },
    ],
  },
]
