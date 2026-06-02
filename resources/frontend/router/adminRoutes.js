const adminRoutes = [
  {
    path: '/a',
    name: 'Admin',
    redirect: { name: 'Admin Dashboard' },
    component: () => import('@/layouts/SystemLayout.vue'),
    meta: { title: 'Admin', requiresFlow: true, role: 'admin' },
    children: [
      {
        path: 'general',
        children: [
          {
            path: 'dashboard',
            name: 'Admin Dashboard',
            component: () => import('@/pages/admin/DashboardPage.vue'),
            meta: { title: 'Dashboard', requiresFlow: true, maintenance: false },
          },
          {
            path: 'notification',
            name: 'Admin Notification',
            component: () => import('@/pages/admin/NotificationPage.vue'),
            meta: { title: 'Notification', requiresFlow: true, maintenance: false },
          },
          {
            path: 'ticket-Desk',
            name: 'Admin Ticket Desk',
            component: () => import('@/pages/admin/TicketDeskPage.vue'),
            meta: { title: 'Ticket Desk', requiresFlow: true, maintenance: false },
          },
        ],
      },
      {
        path: 'management',
        children: [
          {
            path: 'campus',
            name: 'Admin Campus Management',
            component: () => import('@/pages/admin/CampusPage.vue'),
            meta: { title: 'Campus Management', requiresFlow: true, maintenance: false },
          },
          {
            path: 'user',
            name: 'Admin User Management',
            component: () => import('@/pages/admin/UsersPage.vue'),
            meta: { title: 'User Management', requiresFlow: true, maintenance: false },
          },
          {
            path: 'roles-and-permission',
            name: 'Admin Roles and Permission',
            component: () => import('@/pages/admin/CampusPage.vue'),
            meta: { title: 'Roles and Permission Management', requiresFlow: true, maintenance: false },
          },
        ],
      },
    ],
  },
]

export default adminRoutes
