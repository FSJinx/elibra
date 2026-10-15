export const librarianAdministration = [
  // ======== Sections Route =========
  {
    path: 'sections',
    meta: { breadcrumb: 'Sections', title: 'Administration' },
    children: administrationSection,
  },

  // ======== Users Route =========
  {
    path: 'users',
    meta: { breadcrumb: 'Users', title: 'Administration' },
    children: administrationUsers,
  },
]
