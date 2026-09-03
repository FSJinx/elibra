export const menus = {
  admin: {
    general: {
      name: 'General',
      children: {
        dashboard: { path: 'admin.dashboard', name: 'Dashboard', icon: 'grid-1x2' },
      },
    },

    management: {
      name: 'Management',
      children: [
        { path: 'admin.subscriptions', name: 'Subscriptions', icon: 'globe' },
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
        tickets: { path: 'librarian.dashboard.tickets', name: 'Tickets', icon: 'ticket-perforated' },
      },
    },

    circulation: {
      name: 'Circulation',
      children: {
        loans: { path: 'librarian.circulation.loans', name: 'Check-Out / Loans', icon: 'cart-plus' },
        returns: { path: 'librarian.circulation.returns', name: 'Returns', icon: 'arrow-return-left' },
        renewals: { path: 'librarian.circulation.renewals', name: 'Renewals', icon: 'arrow-repeat' },
        holds: { path: 'librarian.circulation.holds', name: 'Holds & Reservations', icon: 'bookmark-check' },
        fines: { path: 'librarian.circulation.fines', name: 'Fines & Penalties', icon: 'cash-coin' },
        attendance: { path: 'librarian.circulation.attendance', name: 'Attendance', icon: 'person-check' },
      },
    },

    collections: {
      name: 'Cataloging & Collections',
      children: {
        catalog: { path: 'librarian.catalog', name: 'Catalog', icon: 'journal-plus' },
        inventory: { path: 'librarian.inventory', name: 'Inventory', icon: 'boxes' },
        author: { path: 'librarian.author', name: 'Authority Control', icon: 'shield-check' },
      },
    },

    patron: {
      name: 'Patrons',
      children: {
        patrons: { path: 'librarian.patrons', name: 'Patrons', icon: 'people' },
        patron_groups: { path: 'librarian.patron-groups', name: 'Patron Groups', icon: 'person-badge' },
        patron_activity: { path: 'librarian.patron-activity', name: 'Patron Activity History', icon: 'activity' },
        borrowing_history: { path: 'librarian.borrowing-history', name: 'Borrowing History', icon: 'clock-history' },
      },
    },

    acquisition: {
      name: 'Acquisitions',
      children: {
        requests: { path: 'librarian.acquisition.requests', name: 'Requests', icon: 'inbox' },
        purchase_orders: { path: 'librarian.acquisition.purchase-orders', name: 'Purchase Orders', icon: 'receipt' },
        vendors: { path: 'librarian.acquisition.vendors', name: 'Vendors', icon: 'building' },
        budget_funds: { path: 'librarian.acquisition.budget-funds', name: 'Budget & Funds', icon: 'wallet2' },
        donations: { path: 'librarian.acquisition.donations', name: 'Donations & Gifts', icon: 'gift' },
      },
    },

    serials: {
      name: 'Serials',
      children: {
        subscriptions: { path: 'librarian.serials.subscriptions', name: 'Subscriptions', icon: 'journals' },
        issue_tracking: { path: 'librarian.serials.issue-tracking', name: 'Issue Tracking', icon: 'newspaper' },
        serials_cataloging: { path: 'librarian.serials.cataloging', name: 'Serials Cataloging', icon: 'collection' },
      },
    },

    reports: {
      name: 'Reports & Analytics',
      children: {
        circulation_reports: { path: 'librarian.reports.circulation', name: 'Circulation', icon: 'bar-chart-line' },
        collection_reports: { path: 'librarian.reports.collection', name: 'Collection', icon: 'pie-chart' },
        overdue_reports: { path: 'librarian.reports.overdue', name: 'Overdue', icon: 'exclamation-triangle' },
        inventory_reports: { path: 'librarian.reports.inventory', name: 'Inventory', icon: 'graph-up-arrow' },
        patrons_reports: { path: 'librarian.reports.patrons', name: 'Patrons', icon: 'person-lines-fill' },
        acquisitions_reports: { path: 'librarian.reports.acquisitions', name: 'Acquisitions', icon: 'file-earmark-bar-graph' },
      },
    },

    administration: {
      name: 'Administration',
      children: {
        staff: { path: 'librarian.administration.staff', name: 'Librarians / Staff', icon: 'person-vcard' },
        roles: { path: 'librarian.administration.roles', name: 'Roles & Permissions', icon: 'key' },
        policies: { path: 'librarian.administration.policies', name: 'Library Policies', icon: 'file-text' },
        branches: { path: 'librarian.administration.branches', name: 'Library Branches', icon: 'geo-alt' },
        barcode: { path: 'librarian.administration.barcode', name: 'Barcode Generator', icon: 'upc-scan' },
      },
    },

    settings: {
      name: 'Settings',
      children: {
        account: { path: 'librarian.settings.account', name: 'Account', icon: 'person-gear' },
        profile: { path: 'librarian.settings.profile', name: 'Profile', icon: 'person-circle' },
        preferences: { path: 'librarian.settings.preferences', name: 'Preferences', icon: 'sliders' },
        system: { path: 'librarian.settings.system', name: 'System Settings', icon: 'gear' },
      },
    },
  },
}
