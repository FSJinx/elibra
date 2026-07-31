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
    ...errorRoutes,
  ],

  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    return { top: 0 }
  },
})

router.beforeEach(async (to, from) => {
  const auth = authStore()
  const { goHome } = useAuth()
  const my = computed(() => auth?.user)
  const accessRoles = String(to.meta?.role ?? '')

  if (auth.token && !auth.isAuthenticated) {
    await auth.getUser()
  }

  if (to.name === 'login' && auth.isAuthenticated) {
    goHome()
  }

  document.title = typeof to.meta.title === 'string' ? 'e-Libra: ' + my.value?.role?.charAt(0).toUpperCase() + my.value?.role?.slice(1) + ' | ' + to.meta.title : 'e-Libra: The ISU-1 Library Management and Resource Monitoring System'

  if (to.meta.maintenance) {
    return { name: 'ServiceUnavailable' }
  }

  if (to.meta.requiresAuth && !accessRoles.split(',').includes(String(my.value?.role ?? ''))) {
    return { name: 'error.403' }
  }
})

export default router
