const errorRoutes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'Not Found',
    component: () => import('@/pages/errors/NotFound.vue'),
  },
  {
    path: '/forbidden',
    name: 'Forbidden',
    component: () => import('@/pages/errors/Forbidden.vue'),
  },
  {
    path: '/unavailable',
    name: 'ServiceUnavailable',
    component: () => import('@/pages/errors/ServiceUnavailable.vue'),
  },
]

export default errorRoutes
