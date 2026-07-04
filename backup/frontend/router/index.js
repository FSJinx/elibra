import { createRouter, createWebHistory } from 'vue-router'
import adminRoutes from './adminRoutes'
import librarianRoutes from './librarianRoutes'
import errorRoutes from './errorRoutes'
import publicRoutes from './publicRoute'

import { roleMap } from '@/constants/roleMap'
import { authStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      // redirect: { name: 'App' },
      component: () => import('../pages/LandingPage.vue'),
      // redirect: { name: 'Login' },
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/pages/auth/LoginPage.vue'),
    },
    {
      path: '/practice',
      name: 'Practice',
      component: () => import('../pages/PracticePage.vue'),
    },

    // Public Routes
    ...publicRoutes,

    // Admin Routes
    ...adminRoutes,

    // Librarian Routes
    ...librarianRoutes,

    // Error Pages
    ...errorRoutes,
  ],

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    return { top: 0 }
  },
})

export let lastRoute = null

router.beforeEach(async (to, from) => {
  const auth = authStore()
  const accessRoles = to.meta?.role

  lastRoute = from

  if (auth.token && !auth.isAuthenticated) {
    await auth.getUser()
  }

  const my = auth?.user
  document.title = to.meta.title ?? 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'

  if (to.meta.maintenance) {
    return { name: 'ServiceUnavailable' }
  }

  if (to.meta.requiresFlow && !accessRoles.split(',').includes(my?.role)) {
    return { name: 'Forbidden' }
  }
})

export default router
