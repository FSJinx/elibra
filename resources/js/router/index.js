import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/public/LandingPage.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/LoginPage.vue'),
    },

    // Admin Routes
    {
      path: '/admin',
      component: () => import('../layouts/admin/AdminLayout.vue'),
      meta: {
        breadcrumb: 'Admin',
      },
      children: [
        {
          path: '/admin',
          name: 'admin',
          component: () => import('../components/admin/contents/AdminDashboard.vue'),
          meta: {
            breadcrumb: 'Dashboard',
          },
        },
      ],
    },

    // Error Pages
    {
      path: '/error',
      name: 'error',
      component: () => import('../views/error/ErrorPage.vue'),
      children: [
        {
          path: 'unauthorized',
          name: 'unauthorized',
          component: () => import('../components/errors/Unauthorized.vue'),
        },
        {
          path: '/:pathMatch(.*)*',
          name: 'not-found',
          component: () => import('../components/errors/NotFound.vue'),
        },
      ],
    },
  ],
})

export default router
