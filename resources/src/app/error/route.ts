export const errorRoutes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'error.404',
    component: () => import('@/app/error/NotFound.vue'),
  },
  {
    path: '/forbidden',
    name: 'error.403',
    meta: { requiresFlow: true },
    component: () => import('@/app/error/Forbidden.vue'),
  },
  {
    path: '/create-account',
    name: 'create',
    meta: { requiresFlow: true },
    component: () => import('@/app/error/Forbidden.vue'),
  },
]
