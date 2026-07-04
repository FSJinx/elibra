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
  },

  librarian: {
    general: {
      dashboard: { path: '', name: '', icon: '' },
    },
    collections: {
      books: { path: '', name: 'Books', icon: 'Books' },
    },
  },
}
