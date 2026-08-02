const menus = {
  admin: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'admin', name: 'Dashboard', icon: 'Home' },
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
        { path: 'admin.subscriptions', name: 'Subscriptions', icon: 'GlobeCheck' },
        { path: 'admin.campus', name: 'Campus', icon: 'Building' },
      ],
    },
  },

  librarian: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'librarian', name: 'Dashboard', icon: 'Home' },
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

export default menus
