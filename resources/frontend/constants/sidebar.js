const adminSidebar = {
  0: [
    {
      name: 'General',
      children: [
        {
          name: 'Dashboard',
          icon: 'LayoutDashboard',
          path: 'Admin Dashboard',
        },
        {
          name: 'Notification',
          icon: 'Bell',
          path: '',
        },
        {
          name: 'Ticket Desk',
          icon: 'HeartHandshake',
          path: '',
        },
      ],
    },
    {
      name: 'Management',
      children: [
        {
          name: 'Campuses',
          icon: 'Building2',
          path: 'Admin Campus Management',
        },
        {
          name: 'Users',
          icon: 'Users',
          path: '',
        },
        {
          name: 'Roles & Permissions',
          icon: 'UserKey',
          path: '',
        },
      ],
    },
  ],
}

export default adminSidebar
