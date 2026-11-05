export const collectionsAcarepo = [
  {
    path: '',
    name: 'librarian.collections.acarepo',
    component: () => import('@/app/librarian/collections/acarepo/AcaRepo.vue'),
  },
  {
    path: ':id/add-to-catalog',
    name: 'librarian.collections.acarepo.catalog',
    component: () => import('@/app/librarian/collections/acarepo/AddToCatalog.vue'),
    beforeEnter: async (to: any) => {
      const acarepo = acaRepoStore()
      const pop = usePopup()
      const crumb = useBreadcrumbStore()

      pop.load()
      try {
        const res = await acarepo.show(to.params.id)
        crumb.set(`${to.name}:${to.params.id}`, `Currently Cataloging: ${res.data.title}`)
      } finally {
        pop.unload()
      }
    },
  },
]
