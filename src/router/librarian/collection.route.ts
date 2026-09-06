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
          },
          {
            path: ':id',
            name: 'librarian.collections.catalog.view',
            component: () => import('@/app/librarian/collections/catalog/ViewCatalog.vue'),
            children: [
              { path: '', meta: { breadcrumb: 'Overview' }, name: 'librarian.collections.catalog.view.overview', component: () => import('@/app/librarian/collections/catalog/view/Overview.vue') },
              { path: 'authors', meta: { breadcrumb: 'Authors' }, name: 'librarian.collections.catalog.view.authors', component: () => import('@/app/librarian/collections/catalog/view/Authors.vue') },
              { path: 'accession', meta: { breadcrumb: 'Accession' }, name: 'librarian.collections.catalog.view.accession', component: () => import('@/app/librarian/collections/catalog/view/Accession.vue') },
              { path: 'acquisition-history', meta: { breadcrumb: 'Acquisition' }, name: 'librarian.collections.catalog.view.acquisition', component: () => import('@/app/librarian/collections/catalog/view/Acquisition.vue') },
            ],
          },
          {
            path: 'add-new',
            meta: { breadcrumb: 'Add New' },
            name: 'librarian.collections.catalog.add-new',
            redirect: { name: 'librarian.collections.catalog.add-new.book' },
            component: () => import('@/app/librarian/collections/catalog/AddCatalog.vue'),
            children: [
              { path: 'book', meta: { breadcrumb: 'Book' }, name: 'librarian.collections.catalog.add-new.book', component: () => import('@/app/librarian/collections/catalog/forms/Book.vue') },
              {
                path: 'academics',
                meta: { breadcrumb: 'Academics' },
                name: 'librarian.collections.catalog.add-new.academics',
                component: () => import('@/app/librarian/collections/catalog/forms/Academics.vue'),
              },
              { path: 'serials', meta: { breadcrumb: 'Serials' }, name: 'librarian.collections.catalog.add-new.serials', component: () => import('@/app/librarian/collections/catalog/forms/Serials.vue') },
            ],
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

      // ======== Acquisition ========
      {
        path: 'acquisition',
        meta: { title: 'Acquisition', breadcrumb: 'Request' },
        children: [
          {
            path: 'acquisition',
            name: 'librarian.collections.acquisition',
            meta: { title: 'Inventory', breadcrumb: 'Inventory' },
            component: () => import('@/app/librarian/collections/acquisition/Acquisition.vue'),
          },
        ],
      },
    ],
  },
]
