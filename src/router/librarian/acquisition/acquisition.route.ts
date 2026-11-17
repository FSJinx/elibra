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
        beforeEnter: async (to: any) => {
          const pop = usePopup()
          const acquisition = acquisitionStore()
          const breadcrumb = useBreadcrumbStore()

          pop.load()
          try {
            await acquisition.show(to.params.id)
          } finally {
            pop.unload()

            const key = `${to.name as string}:${to.params.id}`
            breadcrumb.set(key, `Viewing Acquisition: ${acquisition.currentData?.reference}`)
          }
          return true
        },
      },
    ],
  },
]
