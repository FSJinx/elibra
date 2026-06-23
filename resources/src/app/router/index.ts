import { createRouter, createWebHistory } from 'vue-router'
import type { RouteLocationNormalizedLoaded } from 'vue-router'

// import { roleMap } from '@/constants/roleMap'
import { authStore } from '@/stores/auth'

const router = createRouter({
  // cast import.meta to any to access env in environments where ImportMeta types aren't defined
  history: createWebHistory((import.meta as any).env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('@/App.vue'),
    },
  ],

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    return { top: 0 }
  },
})

export let lastRoute: RouteLocationNormalizedLoaded | null = null

router.beforeEach(async (to, from) => {
  const auth = authStore()
  const accessRoles = String(to.meta?.role ?? '')

  lastRoute = from

  if (auth.token && !auth.isAuthenticated) {
    await auth.getUser()
  }

  const my = auth?.user
  document.title = typeof to.meta.title === 'string'
    ? to.meta.title
    : 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'

  if (to.meta.maintenance) {
    return { name: 'ServiceUnavailable' }
  }

  if (to.meta.requiresFlow && !accessRoles.split(',').includes(String(my?.role ?? ''))) {
    return { name: 'Forbidden' }
  }
})

export default router
