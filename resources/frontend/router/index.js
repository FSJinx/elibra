import { createRouter, createWebHistory } from 'vue-router'
import adminRoutes from './adminRoutes'
import errorRoutes from './errorRoutes'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../pages/PracticePage.vue'),
    },

    // Admin Routes
    ...adminRoutes,

    // Error Pages
    ...errorRoutes,
  ],
})

router.beforeEach((to, from) => {
  document.title = to.meta.title ?? 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'
  if (to.meta.requiresFlow) {
    return { name: 'forbidden' }
  }
})

export default router
