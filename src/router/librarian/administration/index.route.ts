export const librarianAdministration = [
  // ======== Staffs Route =========
  {
    path: 'staffs',
    children: librarianStaffRoute,
  },
  // ======== Sections Route =========
  {
    path: 'sections',
    children: librarianSectionRoute,
  },
]
