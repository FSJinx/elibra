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
    dashboard: {
      name: 'Dashboard',
      children: {
        dashboard: { path: 'librarian.dashboard.overview', name: 'Overview', icon: 'grid' },
        notifications: { path: 'librarian.dashboard.notifications', name: 'Notifications', icon: 'bell' },
        // tickets: { path: '', name: 'Tickets', icon: 'ticket-perforated' },
      },
    },

    // circulation: {
    //   name: 'Circulation',
    //   children: {
    //     loans: { path: '', name: 'Check-Out / Loans', icon: 'cart-plus' },
    //     returns: { path: '', name: 'Returns', icon: 'arrow-return-left' },
    //     renewals: { path: '', name: 'Renewals', icon: 'arrow-repeat' },
    //     holds: { path: '', name: 'Holds & Reservations', icon: 'bookmark-check' },
    //     fines: { path: '', name: 'Fines & Penalties', icon: 'cash-coin' },
    //     attendance: { path: '', name: 'Attendance', icon: 'person-check' },
    //   },
    // },

    collections: {
      name: 'Cataloging & Collections',
      children: {
        catalog: { path: 'librarian.cataloging', name: 'Catalog', icon: 'journal-plus' },
        // inventory: { path: '', name: 'Inventory', icon: 'boxes' },
        // classification: { path: '', name: 'Classification', icon: 'tags' },
        // author: { path: '', name: 'Authority Control', icon: 'shield-check' },
      },
    },

    // patron: {
    //   name: 'Patrons',
    //   children: {
    //     patrons: { path: '', name: 'Patrons', icon: 'people' },
    //     patron_groups: { path: '', name: 'Patron Groups', icon: 'person-badge' },
    //     patron_activity: { path: '', name: 'Patron Activity History', icon: 'activity' },
    //     borrowing_history: { path: '', name: 'Borrowing History', icon: 'clock-history' },
    //   },
    // },

    // acquisition: {
    //   name: 'Acquisitions',
    //   children: {
    //     requests: { path: '', name: 'Requests', icon: 'inbox' },
    //     purchase_orders: { path: '', name: 'Purchase Orders', icon: 'receipt' },
    //     vendors: { path: '', name: 'Vendors', icon: 'building' },
    //     budget_funds: { path: '', name: 'Budget & Funds', icon: 'wallet2' },
    //     donations: { path: '', name: 'Donations & Gifts', icon: 'gift' },
    //   },
    // },

    // serials: {
    //   name: 'Serials',
    //   children: {
    //     subscriptions: { path: '', name: 'Subscriptions', icon: 'journals' },
    //     issue_tracking: { path: '', name: 'Issue Tracking', icon: 'newspaper' },
    //     serials_cataloging: { path: '', name: 'Serials Cataloging', icon: 'collection' },
    //   },
    // },

    // reports: {
    //   name: 'Reports & Analytics',
    //   children: {
    //     circulation_reports: { path: '', name: 'Circulation', icon: 'bar-chart-line' },
    //     collection_reports: { path: '', name: 'Collection', icon: 'pie-chart' },
    //     overdue_reports: { path: '', name: 'Overdue', icon: 'exclamation-triangle' },
    //     inventory_reports: { path: '', name: 'Inventory', icon: 'graph-up-arrow' },
    //     patrons_reports: { path: '', name: 'Patrons', icon: 'person-lines-fill' },
    //     acquisitions_reports: { path: '', name: 'Acquisitions', icon: 'file-earmark-bar-graph' },
    //   },
    // },

    // administration: {
    //   name: 'Administration',
    //   children: {
    //     staff: { path: '', name: 'Librarians / Staff', icon: 'person-vcard' },
    //     roles: { path: '', name: 'Roles & Permissions', icon: 'key' },
    //     policies: { path: '', name: 'Library Policies', icon: 'file-text' },
    //     branches: { path: '', name: 'Library Branches', icon: 'geo-alt' },
    //     barcode: { path: '', name: 'Barcode Generator', icon: 'upc-scan' },
    //   },
    // },

    // settings: {
    //   name: 'Settings',
    //   children: {
    //     account: { path: '', name: 'Account', icon: 'person-gear' },
    //     profile: { path: '', name: 'Profile', icon: 'person-circle' },
    //     preferences: { path: '', name: 'Preferences', icon: 'sliders' },
    //     system: { path: '', name: 'System Settings', icon: 'gear' },
    //   },
    // },
  },
}
