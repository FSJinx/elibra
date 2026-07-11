export const errorRoutes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/app/error/NotFound.vue'),
  },
  {
    path: '/forbidden',
    name: 'Forbidden',
    component: () => import('@/app/error/Forbidden.vue'),
  },
]
