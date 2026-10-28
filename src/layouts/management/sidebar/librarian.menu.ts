export const librarianMenu = [
  {
    name: 'Dashboard',
    children: [
      { path: 'librarian.dashboard.overview', name: 'Overview', icon: 'grid' },
      { path: 'librarian.dashboard.notifications', name: 'Notifications', icon: 'bell' },
      { path: 'librarian.dashboard.tickets', name: 'Tickets', icon: 'ticket-perforated' },
    ],
  },

  {
    name: 'Circulation',
    children: [
      // { path: 'librarian.circulation.check_out', name: 'Check Out', icon: 'journal-arrow-up' },
      // { path: 'librarian.circulation.issue', name: 'Issue', icon: 'ui-checks' },
      { path: 'librarian.circulation.loans', name: 'Loans', icon: 'bag-plus' },
      // returns: { path: 'librarian.circulation.returns', name: 'Returns', icon: 'arrow-return-left' },
      // renewals: { path: 'librarian.circulation.renewals', name: 'Renewals', icon: 'arrow-repeat' },
      // holds: { path: 'librarian.circulation.holds', name: 'Holds & Reservations', icon: 'bookmark-check' },
      // fines: { path: 'librarian.circulation.fines', name: 'Fines & Penalties', icon: 'cash-coin' },
      { path: 'librarian.circulation.attendance', name: 'Attendance', icon: 'person-check' },
    ],
  },

  {
    name: 'Collections',
    children: [
      { path: 'librarian.collections.catalog', name: 'Catalog', icon: 'journal-plus' },
      { path: 'librarian.collections.inventory', name: 'Inventory', icon: 'boxes' },
      { path: 'librarian.collections.acarepo', name: 'AcaRepo', icon: 'mortarboard', new: true },
    ],
  },

  {
    name: 'Acquisitions',
    children: [
      { path: 'librarian.acquisition', name: 'Acquisition', icon: 'receipt' },
      { path: 'librarian.acquisition-requests', name: 'Acquisition Request', icon: 'box-arrow-in-down' },
    ],
  },

  // patron: {
  //   name: 'Patrons',
  //   children: {
  //     patrons: { path: 'librarian.patrons', name: 'Patrons', icon: 'people' },
  //     patron_groups: { path: 'librarian.patron-groups', name: 'Patron Groups', icon: 'person-badge' },
  //     patron_activity: { path: 'librarian.patron-activity', name: 'Patron Activity History', icon: 'activity' },
  //     borrowing_history: { path: 'librarian.borrowing-history', name: 'Borrowing History', icon: 'clock-history' },
  //   },
  // },

  // serials: {
  //   name: 'Serials',
  //   children: {
  //     subscriptions: { path: 'librarian.serials.subscriptions', name: 'Subscriptions', icon: 'journals' },
  //     issue_tracking: { path: 'librarian.serials.issue-tracking', name: 'Issue Tracking', icon: 'newspaper' },
  //     serials_cataloging: { path: 'librarian.serials.cataloging', name: 'Serials Cataloging', icon: 'collection' },
  //   },
  // },

  // reports: {
  //   name: 'Reports & Analytics',
  //   children: {
  //     circulation_reports: { path: 'librarian.reports.circulation', name: 'Circulation', icon: 'bar-chart-line' },
  //     collection_reports: { path: 'librarian.reports.collection', name: 'Collection', icon: 'pie-chart' },
  //     overdue_reports: { path: 'librarian.reports.overdue', name: 'Overdue', icon: 'exclamation-triangle' },
  //     inventory_reports: { path: 'librarian.reports.inventory', name: 'Inventory', icon: 'graph-up-arrow' },
  //     patrons_reports: { path: 'librarian.reports.patrons', name: 'Patrons', icon: 'person-lines-fill' },
  //     acquisitions_reports: { path: 'librarian.reports.acquisitions', name: 'Acquisitions', icon: 'file-earmark-bar-graph' },
  //   },
  // },

  {
    name: 'Administration',
    children: [
      { path: 'librarian.sections', name: 'Sections', icon: 'building-add' },
      { path: 'librarian.users', name: 'Users', icon: 'person-square' },
    ],
  },

  {
    name: 'Settings',
    children: [
      { path: 'librarian.settings.account', name: 'Account', icon: 'person-gear' },
      { path: 'librarian.settings.profile', name: 'Profile', icon: 'person-circle' },
      { path: 'librarian.settings.preferences', name: 'Preferences', icon: 'sliders' },
      { path: 'librarian.settings.system', name: 'System Settings', icon: 'gear' },
    ],
  },
]
