export const librarianCataloging = [
  {
    path: 'catalog',
    meta: { breadcrumb: 'Catalog' },
    redirect: { name: 'librarian.catalog' },
    children: [
      {
        path: '',
        name: 'librarian.catalog',
        component: () => import('@/app/librarian/cataloging/catalog/Catalog.vue'),
      },
      {
        path: ':id',
        name: 'librarian.catalog.view',
        component: () => import('@/app/librarian/cataloging/catalog/ViewCatalog.vue'),
        children: [
          { path: '', meta: { breadcrumb: 'Overview' }, name: 'librarian.cataloging.catalog.view.overview', component: () => import('@/app/librarian/cataloging/catalog/view/Overview.vue') },
          { path: 'authors', meta: { breadcrumb: 'Authors' }, name: 'librarian.cataloging.catalog.view.authors', component: () => import('@/app/librarian/cataloging/catalog/view/Authors.vue') },
          { path: 'accession', meta: { breadcrumb: 'Accession' }, name: 'librarian.cataloging.catalog.view.accession', component: () => import('@/app/librarian/cataloging/catalog/view/Accession.vue') },
          { path: 'acquisition-history', meta: { breadcrumb: 'Acquisition' }, name: 'librarian.cataloging.catalog.view.acquisition', component: () => import('@/app/librarian/cataloging/catalog/view/Acquisition.vue') },
        ],
      },
      {
        path: 'add-new',
        meta: { breadcrumb: 'Add New' },
        name: 'librarian.catalog.add-new',
        redirect: { name: 'librarian.cataloging.add-new.book' },
        component: () => import('@/app/librarian/cataloging/catalog/AddCatalog.vue'),
        children: [
          { path: 'book', meta: { breadcrumb: 'Book' }, name: 'librarian.catalog.add-new.book', component: () => import('@/app/librarian/cataloging/catalog/forms/Book.vue') },
          { path: 'academics', meta: { breadcrumb: 'Academics' }, name: 'librarian.catalog.add-new.academics', component: () => import('@/app/librarian/cataloging/catalog/forms/Academics.vue') },
          { path: 'serials', meta: { breadcrumb: 'Serials' }, name: 'librarian.catalog.add-new.serials', component: () => import('@/app/librarian/cataloging/catalog/forms/Serials.vue') },
        ],
      },
    ],
  },
  {
    path: 'inventory',
    name: 'librarian.inventory',
    meta: { breadcrumb: 'Inventory' },
  },
  {
    path: 'author',
    name: 'librarian.author',
    meta: { breadcrumb: 'Authors' },
  },
]
