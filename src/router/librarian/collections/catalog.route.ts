const pop = usePopup()

export const collectionsCatalog = [
  {
    path: '',
    name: 'librarian.collections.catalog',
    component: () => import('@/app/librarian/collections/catalog/Catalog.vue'),
  },
  {
    path: ':id',
    name: 'librarian.collections.catalog.view',
    redirect: { name: 'librarian.collections.catalog.view.overview' },
    component: () => import('@/app/librarian/collections/catalog/ViewCatalog.vue'),
    beforeEnter: async (to: any) => {
      const item = useItemStore()

      if (item.currentData?.id != to.params.id) {
        pop.load()

        try {
          await item.show(to.params.id)
          return true
        } catch (e) {
          return false
        } finally {
          pop.unload()
        }
      }
    },
    children: [
      {
        path: '',
        meta: { title: 'Overview', breadcrumb: 'Overview' },
        name: 'librarian.collections.catalog.view.overview',
        component: () => import('@/app/librarian/collections/catalog/view/Overview.vue'),
      },
      { path: 'authors', meta: { breadcrumb: 'Authors' }, name: 'librarian.collections.catalog.view.authors', component: () => import('@/app/librarian/collections/catalog/view/Authors.vue') },
      { path: 'accession', meta: { breadcrumb: 'Accession' }, name: 'librarian.collections.catalog.view.accession', component: () => import('@/app/librarian/collections/catalog/view/Accession.vue') },
      { path: 'acquisition-history', meta: { breadcrumb: 'Acquisition' }, name: 'librarian.collections.catalog.view.acquisition', component: () => import('@/app/librarian/collections/catalog/view/Acquisition.vue') },
    ],
  },
  {
    path: 'new',
    meta: { title: 'Cataloging', breadcrumb: 'New Item' },
    name: 'librarian.collections.catalog.new',
    component: () => import('@/app/librarian/collections/catalog/AddCatalog.vue'),
  },
]
