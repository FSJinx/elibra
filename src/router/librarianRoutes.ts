const librarianRoutes = [
  {
    path: '/librarian',
    meta: { breadcrumb: 'Librarian', requiresAuth: true, role: 'librarian' },
    name: 'librarian',
    redirect: { name: 'librarian.dashboard' },
    component: () => import('@/layouts/management/ManagementLayout.vue'),
    children: [
      ...librarianDashboard, // ======== DASHBOARD ROUTES =========
      ...librarianCataloging, // ======== CATALOGING ROUTES =========
      ...librarianCirculation, // ======== CIRCULATION ROUTES =========
      ...librarianPatron, // ======== PATRON ROUTES =========
      ...librarianAcquisition, // ======== ACQUISITION ROUTES =========
      ...librarianSerial, // ======== SERIAL ROUTES =========
      ...librarianReport, // ======== REPORT ROUTES =========
      ...librarianAdministration, // ======== ADMINISTRATION ROUTES =========
      ...librarianSettings, // ======== SETTINGS ROUTES =========
    ],
  },
]

export default librarianRoutes
