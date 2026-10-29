export const librarianCollections = [
  {
    path: '',
    meta: { title: 'Collections' },
    children: [
      // ======== Catalog Route ========
      {
        path: 'catalog',
        meta: { breadcrumb: 'Catalog' },
        children: collectionsCatalog,
      },

      // ======== Inventory Route ========
      {
        path: 'inventory',
        meta: { breadcrumb: 'Inventory' },
        children: collectionsInventory,
      },

      // ======== AcaRepo Route ========
      {
        path: 'acarepo',
        meta: { breadcrumb: 'AcaRepo' },
        children: collectionsAcarepo,
      },
    ],
  },
]
