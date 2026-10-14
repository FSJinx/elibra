export const librarianAdministration = [
  // ======== Sections Route =========
  {
    path: 'sections',
    meta: { breadcrumbs: 'Sections', title: 'Administration' },
    children: administrationSection,
  },

  // ======== Users Route =========
  {
    path: 'users',
    meta: { breadcrumbs: 'Users', title: 'Administration' },
    children: administrationUsers,
  },
]
