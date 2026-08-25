export const menus = {
  admin: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'admin.dashboard', name: 'Dashboard', icon: 'grid-1x2' },
      },
    },

    // collections: {
    //   name: 'Collections',
    //   children: {
    //     academic: { path: '', name: 'Academics', icon: 'GraduationCap' },
    //     books: { path: '', name: 'Books', icon: 'BookOpen' },
    //     serials: { path: '', name: 'Serials', icon: 'Newspaper' },
    //     equipment: { path: '', name: 'Equipments', icon: 'Toolbox' },
    //   },
    // },

    management: {
      name: 'Management',
      children: [
        // { path: 'admin.subscriptions', name: 'Subscriptions', icon: 'GlobeCheck' },
        { path: 'admin.campus', name: 'Campus', icon: 'buildings' },
        { path: 'admin.users', name: 'Users', icon: 'people' },
      ],
    },
  },

  librarian: {
    overview: {
      name: 'Overview',
      children: {
        dashboard: { path: 'librarian.dashboard', name: 'Dashboard', icon: 'grid' },
        notifications: { path: 'librarian.notifications', name: 'Notifications', icon: 'bell' },
      },
    },

    collections: {
      name: 'Collections',
      children: {
        academic: { path: 'librarian.academics', name: 'Academics', icon: 'GraduationCap', code: 'collacademic' },
        // books: { path: '', name: 'Books', icon: 'BookOpen', code: 'collbooks' },
        // serials: { path: '', name: 'Serials', icon: 'Newspaper', code: 'collserials' },
        // equipment: { path: '', name: 'Equipments', icon: 'Toolbox', code: 'collequipment' },
      },
    },
  },
}
