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
    path: '/maintenance',
    name: 'error.503',
    meta: { requiresFlow: true },
    component: () => import('@/app/error/ServiceUnavailable.vue'),
  },
]
