export const adminStandard = [
  {
    path: '',
    meta: { title: 'Standards' },
    children: [
      {
        path: 'item_types',
        meta: { breadcrumb: 'Item Types' },
        children: standardItemType,
      },
      {
        path: 'item_categories',
        meta: { breadcrumb: 'Item Categories' },
        children: standardCategory,
      },
      {
        path: 'patron_type',
        meta: { breadcrumb: 'Patron Types' },
        children: standardPatronType,
      },
    ],
  },
]
