export const adminMenu = [
  {
    name: 'Dashboard',
    children: [
      { path: 'admin.overview', name: 'Overview', icon: 'speedometer2' },
      { path: '', name: 'Notifications', icon: 'bell' },
      { path: '', name: 'Tickets', icon: 'ticket-perforated' },
    ],
  },

  {
    name: 'Organization',
    children: [
      { path: 'admin.campus', name: 'Campus', icon: 'buildings' },
      { path: 'admin.libraries', name: 'Libraries', icon: 'bank2' },
      { path: 'admin.vendors', name: 'Vendors', icon: 'shop' },
    ],
  },

  {
    name: 'Users & Access',
    children: [
      { path: '', name: 'Accounts', icon: 'people' },
      { path: '', name: 'Roles & Permissions', icon: 'person-lock' },
      { path: '', name: 'Login Activity', icon: 'clock-history' },
    ],
  },

  {
    name: 'Standards',
    children: [
      { path: 'admin.item_types', name: 'Item Types', icon: 'tags' },
      { path: 'admin.item_categories', name: 'Item Categories', icon: 'collection' },
      { path: 'admin.patron_type', name: 'Patron Type', icon: 'people' },
      { path: 'admin.loan_modes', name: 'Loan Modes', icon: 'cash-coin' },
    ],
  },

  {
    name: 'Authority Control',
    children: [
      { path: '', name: 'Authors', icon: 'person' },
      { path: 'admin.publishers', name: 'Publishers', icon: 'building' },
      { path: 'admin.languages', name: 'Languages', icon: 'translate' },
    ],
  },

  {
    name: 'Policies',
    children: [
      { path: '', name: 'Circulation Rules', icon: 'arrow-left-right' },
      { path: '', name: 'Fines & Fees', icon: 'cash-stack' },
      { path: '', name: 'Calendar', icon: 'calendar3' },
    ],
  },
]
