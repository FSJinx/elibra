const publicRoutes = [
  {
    path: '/opac',
    name: 'Public OPAC',
    component: () => import('@/pages/OPACPage.vue'),
    meta: { title: 'ISU Opac', requiresFlow: false },
  },
]

export default publicRoutes
