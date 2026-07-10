export const errorRoutes = [
  {
    path: '/:pathMatch(.*)*',
    name: 'Forbidden',
    component: () => import('@/app/error/Forbidden.vue'),
  },
  {
    path: '/forbidden',
    name: 'Forbidden',
    component: () => import('@/app/error/Forbidden.vue'),
  },
]
