const pop = usePopup()
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
        beforeEnter: async (to: any, from: any) => {
          const acquisition = useAcquisitionStore()
          if (!acquisition.fetchData) {
            acquisition.fetch()
          }
          return true
        },
      },
      {
        path: ':id',
        name: 'librarian.acquisition.lines',
        meta: { breadcrumb: 'Viewing:' },
        component: () => import('@/app/librarian/acquisition/acquisition/AcquisitionLines.vue'),
        beforeEnter: async (to: any, from: any) => {
          const lines = useAcquisitionLinesStore()
          const acquisition = useAcquisitionStore()

          if (!acquisition.currentData) {
            acquisition.read(to.params.id)
          }

          pop.load()
          await lines.fetch(to.params.id)
          pop.unload()
          return true
        },
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
