export const librarianDashboard = [
  {
    path: 'dashboard',
    name: 'librarian.dashboard',
    meta: { breadcrumb: 'Dashboard' },
    redirect: { name: 'librarian.dashboard.overview' },
    children: [
      {
        path: 'overview',
        name: 'librarian.dashboard.overview',
        meta: { title: 'Overview', description: "Here is your library's performance overview for today.", breadcrumb: 'Overview' },
        component: () => import('@/app/librarian/dashboard/overview/Overview.vue'),
      },
      {
        path: '',
        name: 'librarian.dashboard.notifications',
        meta: { title: 'Notifications', breadcrumb: 'Notifications' },
        component: () => import('@/app/librarian/dashboard/notifications/Notifications.vue'),
      },
      {
        path: 'tickets',
        name: 'librarian.dashboard.tickets',
        meta: { title: 'Tickets', breadcrumb: 'Tickets' },
        component: () => import('@/app/librarian/dashboard/tickets/Tickets.vue'),
      },
    ],
  },
]
