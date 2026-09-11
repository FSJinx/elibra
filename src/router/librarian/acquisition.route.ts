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
        component: () => import('@/app/librarian/acquisition/acquisition/AcquisitionLines.vue'),
        beforeEnter: async (to: any) => {
          const lines = useAcquisitionLinesStore()
          const acquisition = useAcquisitionStore()
          const breadcrumb = useBreadcrumbStore()

          if (!acquisition.currentData) {
            await acquisition.read(to.params.id)
          }

          try {
            pop.load()
            await lines.fetch(to.params.id)
          } finally {
            pop.unload()
            const key = `${to.name as string}:${to.params.id}`
            breadcrumb.set(key, `Viewing Acquisition: ${acquisition.currentData?.acquisition_id}`)
          }

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
