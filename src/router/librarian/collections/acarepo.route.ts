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
        if (acarepo.currentData?.on_catalog) {
          pop.info('This thesis is already on the catalog')
          return false
        } else {
          crumb.set(`${to.name}:${to.params.id}`, `Currently Cataloging: ${res.data.title}`)
          pop.unload()
          return true
        }
      } catch (e: any) {
        pop.error(e.response?.data?.message)
        return false
      }
    },
  },
]
