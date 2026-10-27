export const acquisitionAcquisition = [
  {
    path: '',
    name: 'librarian.acquisition',
    component: () => import('@/app/librarian/acquisition/acquisition/Acquisition.vue'),
  },
  {
    path: ':id',
    children: [
      {
        path: '',
        name: 'librarian.acquisition.lines',
        component: () => import('@/app/librarian/acquisition/acquisition_line/AcquisitionLines.vue'),
      },
    ]
  },
]
