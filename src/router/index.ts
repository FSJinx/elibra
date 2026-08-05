import { createRouter, createWebHistory } from 'vue-router'

// import { roleMap } from '@/constants/roleMap'
import { authStore } from '@/stores/authStore'

// Route imports

const router = createRouter({
  // cast import.meta to any to access env in environments where ImportMeta types aren't defined
  history: createWebHistory((import.meta as any).env.BASE_URL),
  routes: [
    // Public Route
    ...publicRoutes,

    // Auth
    ...authRoute,

    {
      path: '/db',
      component: () => import('@/app/DatabaseSchema.vue'),
    },

    {
      path: '/test',
      component: () => import('@/app/Test.vue'),
    },

    // Admin Route
    ...adminRoutes,

    // Librarian Route
    ...librarianRoutes,

    // Error Routes
    {
      path: '/:pathMatch(.*)*',
      name: 'error.404',
      component: { render: () => null },
    },
  ],

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    return { top: 0 }
  },
})

router.beforeEach(async (to, from) => {
  const matched = to.matched

  const requiresAuth = matched.some((route) => route.meta.requiresAuth)
  const role = matched.find((route) => route.meta.role)?.meta.role
  const permission = matched.find((route) => route.meta.permission)?.meta.permission
  const maintenance = matched.find((route) => route.meta.maintenance)?.meta.maintenance

  // ======== STORES ===========
  const store = authStore()

  // ======== COMPOSABLES ===========
  const auth = useAuth()
  const error = useError()
  const preload = usePreloader()

  const accessRoles = String(role ?? '')

  // ======== AUTHENTICATED PRELOAD ===========
  if (store.token && !store.isAuthenticated) {
    await auth.getUser()
  }

  await preload

  if (to.name === 'login' && store.isAuthenticated) {
    auth.goHome()
  }

  if (maintenance) {
    // Some logic that triggers a popup showing that the page is under maintenance
    error.maintenance()
    return false
  }

  if (to.name === 'error.404') {
    console.log('Wala kayong pupuntahan maem.')
    error.notFound(to.fullPath)
    return
  }

  if (requiresAuth && !accessRoles.split(',').includes(String(store.user?.role ?? ''))) {
    error.forbidden()
    router.resolve(from)
    return false
  }

  document.title = typeof to.meta.title === 'string' ? 'e-Libra: ' + store.user?.role?.charAt(0).toUpperCase() + store.user?.role?.slice(1) + ' | ' + to.meta.title : 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'
})

export default router
