const pop = usePopup()

export const librarianCataloging = [
  {
    path: 'collections',
    meta: { title: 'Collections', breadcrumb: 'Collections' },
    redirect: { name: 'librarian.collections.catalog' },
    children: [
      // ======== Catalog Route =========
      {
        path: 'catalog',
        redirect: { name: 'librarian.collections.catalog' },
        children: [
          {
            path: '',
            name: 'librarian.collections.catalog',
            component: () => import('@/app/librarian/collections/catalog/Catalog.vue'),
            beforeEnter: async (to: any) => {
              const item = useItemStore()
              if (!item.data) {
                pop.load()
                await item.fetch(true)
                pop.unload()
              }

              return true
            },
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
        ],
      },

      // ======== Inventory ========
      {
        path: 'inventory',
        name: 'librarian.collections.inventory',
        meta: { title: 'Inventory', breadcrumb: 'Inventory' },
        component: () => import('@/app/librarian/collections/inventory/Inventory.vue'),
      },
    ],
  },
]
