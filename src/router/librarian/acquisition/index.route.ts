export const librarianAcquisition = [
  // ======== Acquisition ========
  {
    path: 'acquisition',
    meta: { title: 'Acquisition', breadcrumb: 'Acquisition' },
    children: acquisitionAcquisition,
  },

  // ======== Acquisition Requests ========
  {
    path: 'acquisition-requests',
    name: 'librarian.acquisition-requests',
    meta: { title: 'Acquisition Requests', breadcrumb: 'Acquisition Requests' },
    component: () => import('@/app/librarian/acquisition/request/Requests.vue'),
  },
]
