const menus = [
  {
    name: 'General',
    child: [
      {
        name: 'Dashboard',
        icon: 'LayoutDashboard',
        path: 'admin',
      },
      {
        name: 'Notification',
        icon: 'Bell',
        path: '',
      },
      {
        name: 'Ticket Desk',
        icon: 'Ticket',
        path: '',
      },
    ],
  },
  {
    name: 'Management',
    child: [
      {
        name: 'Campus Management',
        icon: 'Building2',
        path: '',
      },
      {
        name: 'User Management',
        icon: 'Users',
        path: '',
      },
      {
        name: 'Roles & Permission',
        icon: 'UserLock',
        path: '',
      },
    ],
  },
  {
    name: 'Settings',
    child: [
      {
        name: 'Profile',
        icon: 'CircleUser',
        path: '',
      },
      {
        name: 'Account',
        icon: 'User',
        path: '',
      },
      {
        name: 'Accessibility',
        icon: 'Hand',
        path: '',
      },
    ],
  },
]

export default menus
