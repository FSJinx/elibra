export const menus = {
  admin: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'Admin', name: 'Dashboard', icon: 'Home' },
      },
    },

    collections: {
      name: 'Collections',
      children: {
        academic: { path: '', name: 'Academics', icon: 'GraduationCap' },
        books: { path: '', name: 'Books', icon: 'BookOpen' },
        serials: { path: '', name: 'Serials', icon: 'Newspaper' },
        equipment: { path: '', name: 'Equipments', icon: 'Toolbox' },
      },
    },

    management: {
      name: 'Management',
      children: [{ path: 'Admin Subscriptions', name: 'Subscriptions', icon: 'GlobeCheck' }],
    },
  },

  librarian: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'Librarian', name: 'Dashboard', icon: 'Home' },
      },
    },

    collections: {
      name: 'Collections',
      children: {
        academic: { path: '', name: 'Academics', icon: 'GraduationCap', code: 'collacademic' },
        books: { path: '', name: 'Books', icon: 'BookOpen', code: 'collbooks' },
        serials: { path: '', name: 'Serials', icon: 'Newspaper', code: 'collserials' },
        equipment: { path: '', name: 'Equipments', icon: 'Toolbox', code: 'collequipment' },
      },
    },
  },

}
