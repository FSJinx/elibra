export const librarianCollections = [
  // ======== Catalog Route ========
  {
    path: 'catalog',
    meta: { title: 'Collections', breadcrumb: 'Catalog' },
    children: collectionsCatalog,
  },

  // ======== Inventory Route ========
  {
    path: 'inventory',
    meta: { title: 'Collections', breadcrumb: 'Inventory' },
    children: collectionsInventory,
  },
]
