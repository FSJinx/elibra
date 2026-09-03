export const errorRoutes = [
  // Error Routes
  {
    path: '/:pathMatch(.*)*',
    name: 'error.404',
    component: { render: () => null },
  },
]
