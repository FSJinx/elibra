export const librarianAcquisition = [
  // ======== Acquisition ========
  {
    path: 'acquisition',
    meta: { title: 'Acquisition', breadcrumb: 'Acquisition' },
    redirect: { name: 'librarian.acquisition' },
    children: [
      {
        path: '',
        name: 'librarian.acquisition',
        component: () => import('@/app/librarian/acquisition/acquisition/Acquisition.vue'),
      },
      {
        path: 'view',
        name: 'librarian.acquisition.view',
        meta: { breadcrumb: 'Viewing:' },
        component: () => import('@/app/librarian/acquisition/acquisition/ViewAcquisitions.vue'),
      },
    ],
  },

  // ======== Acquisition Requests ========
  {
    path: 'acquisition-requests',
    name: 'librarian.acquisition-requests',
    component: () => import('@/app/librarian/acquisition/request/Requests.vue'),
  },
]
