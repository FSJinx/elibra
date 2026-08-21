import adminCampusRoutes from '@/router/admin/campusRoutes'

export const adminRoutes = [
  {
    path: '/admin',
    name: 'admin',
    meta: {
      breadcrumb: 'Admin',
      requiresAuth: true,
      role: 'admin',
    },
    redirect: { name: 'admin.dashboard' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),

    children: [
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        meta: {
          breadcrumb: 'Dashboard',
          title: 'Dashboard',
          description: "This is you're today's preview.",
          maintenance: false,
          permission: '',
        },
        component: () => import('@/app/admin/dashboard/Dashboard.vue'),
      },
      {
        // Manage Subscriptions
        path: 'subscriptions',
        name: 'admin.subscriptions',
        meta: {
          title: 'Subscriptions',
          description: "Manage your campus' online subscriptions.",
          breadcrumb: 'Dashboard',
          permission: '',
          maintenance: false,
        },
        component: () => import('@/app/admin/subscriptions/Subscriptions.vue'),
      },
      {
        // Campus List
        path: 'campus',
        name: 'admin.campus',
        meta: {
          breadcrumb: 'Campus Management',
          permission: '',
          maintenance: false,
        },
        redirect: { name: 'admin.campus.list' },
        children: adminCampusRoutes,
      },
      {
        // Campus List
        path: 'users',
        name: 'admin.users',
        meta: {
          title: 'User Management',
          description: 'Manage users accross campuses.',
          permission: '',
          maintenance: false,
        },
        component: () => import('@/app/admin/users/Users.vue'),
      },
    ],
  },
]
