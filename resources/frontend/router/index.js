import { createRouter, createWebHistory } from 'vue-router'
import adminRoutes from './adminRoutes'
import errorRoutes from './errorRoutes'
import { roleMap } from '@/constants/roleMap'

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
let user = {
  name: 'John Doe',
  role: 0,
}

router.beforeEach((to, from) => {
  lastRoute = from
  console.log('Last Page:', lastRoute.name)

  document.title = to.meta.title ?? 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'

  if (to.meta.maintenance) {
    return { name: 'ServiceUnavailable' }
  }

  if (to.meta.requiresFlow && roleMap[user.role]?.name !== to.meta.role) {
    return { name: 'Forbidden' }
  }
})

export default router
