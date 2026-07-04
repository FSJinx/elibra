const sidebar = {
  // Admin Sidebar Menu
  admin: [
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
          path: 'Admin Notification',
        },
        {
          name: 'Ticket Desk',
          icon: 'HeartHandshake',
          path: 'Admin Ticket Desk',
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
          path: 'Admin User Management',
        },
        {
          name: 'Roles & Permissions',
          icon: 'UserKey',
          path: 'Admin Roles and Permission',
        },
      ],
    },
  ],

  // Librarian Sidebar Menu
  librarian: [
    {
      name: 'General',
      children: [
        {
          name: 'Dashboard',
          icon: 'LayoutDashboard',
          path: 'Librarian Dashboard',
        },
        // {
        //   name: 'Collections',
        //   icon: 'LibraryBig',
        //   path: '',
        // },
        // {
        //   name: 'Notification',
        //   icon: 'Bell',
        //   path: '',
        // },
        // {
        //   name: 'Feedbacks',
        //   icon: 'MessageSquareHeart',
        //   path: '',
        // },
        // {
        //   name: 'Social',
        //   icon: 'MessagesSquare',
        //   path: '',
        // },
      ],
    },
    // {
    //   name: 'Tools',
    //   children: [
    //     {
    //       name: 'AcaRepo',
    //       icon: 'GraduationCap',
    //       path: '',
    //     },
    //     {
    //       name: 'Acquisition',
    //       icon: 'PackagePlus',
    //       path: '',
    //     },
    //     {
    //       name: 'Circulation',
    //       icon: 'RefreshCcw',
    //       path: '',
    //     },
    //     {
    //       name: 'Reports & Export',
    //       icon: 'FileSpreadsheet',
    //       path: '',
    //     },
    //     {
    //       name: 'Return Transaction',
    //       icon: 'Undo2',
    //       path: '',
    //     },
    //     {
    //       name: 'Technical',
    //       icon: 'Wrench',
    //       path: '',
    //     },
    //   ],
    // },
    // {
    //   name: 'Files',
    //   children: [
    //     {
    //       name: 'Academic',
    //       icon: 'GraduationCap',
    //       path: '',
    //     },
    //     {
    //       name: 'e-Resource',
    //       icon: 'Laptop',
    //       path: '',
    //     },
    //     {
    //       name: 'Filipiniana',
    //       icon: 'Flag',
    //       path: '',
    //     },
    //     {
    //       name: 'General',
    //       icon: 'BookOpen',
    //       path: '',
    //     },
    //     {
    //       name: 'Museum',
    //       icon: 'Landmark',
    //       path: '',
    //     },
    //     {
    //       name: 'Periodical',
    //       icon: 'Newspaper',
    //       path: '',
    //     },
    //     {
    //       name: 'References',
    //       icon: 'BookMarked',
    //       path: '',
    //     },
    //     {
    //       name: 'Serials',
    //       icon: 'Files',
    //       path: '',
    //     },
    //   ],
    // },
    // {
    //   name: 'Bin',
    //   children: [
    //     {
    //       name: 'Archived',
    //       icon: 'Archive',
    //       path: '',
    //     },
    //     {
    //       name: 'Condemned',
    //       icon: 'Trash2',
    //       path: '',
    //     },
    //   ],
    // },
    {
      name: 'Management',
      children: [
        {
          name: 'Users',
          icon: 'Users',
          path: 'Librarian User Management',
          children: [
            {
              name: 'Librarians',
              path: '',
            },
            {
              name: 'Patrons',
              path: '',
            },
          ],
        },
        // {
        //   name: 'Library',
        //   icon: 'Building2',
        //   path: '',
        // },
        // {
        //   name: 'Roles & Permissions',
        //   icon: 'ShieldCheck',
        //   path: '',
        // },
      ],
    },
  ],
}

export default sidebar
