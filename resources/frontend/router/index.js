import { createRouter, createWebHistory } from 'vue-router'
import adminRoutes from './adminRoutes'
import librarianRoutes from './librarianRoutes'
import errorRoutes from './errorRoutes'
import { roleMap } from '@/constants/roleMap'
import { useUserStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('../pages/LandingPage.vue'),
    },

    // Admin Routes
    ...adminRoutes,

    // Librarian Routes
    ...librarianRoutes,

    // Error Pages
    ...errorRoutes,
  ],
})

export let lastRoute = null

router.beforeEach((to, from) => {
  const my = useUserStore()
  lastRoute = from
  console.log('Last Page:', lastRoute.name)

  document.title = to.meta.title ?? 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'

  if (to.meta.maintenance) {
    return { name: 'ServiceUnavailable' }
  }

  if (to.meta.requiresFlow && my.role !== to.meta.role) {
    return { name: 'Forbidden' }
  }
})

export default router
