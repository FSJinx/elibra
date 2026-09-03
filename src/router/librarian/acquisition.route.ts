export const librarianAcquisition = [
  {
    path: 'acquisition',
    name: 'librarian.acquisition',
    meta: { breadcrumb: 'Acquisitions' },
    children: [
      { path: 'requests', name: 'librarian.acquisition.requests', meta: { title: 'Requests', breadcrumb: 'Requests' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
      { path: 'purchase-orders', name: 'librarian.acquisition.purchase-orders', meta: { title: 'Purchase Orders', breadcrumb: 'Purchase Orders' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
      { path: 'vendors', name: 'librarian.acquisition.vendors', meta: { title: 'Vendors', breadcrumb: 'Vendors' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
      { path: 'budget-funds', name: 'librarian.acquisition.budget-funds', meta: { title: 'Budget & Funds', breadcrumb: 'Budget & Funds' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
      { path: 'donations', name: 'librarian.acquisition.donations', meta: { title: 'Donations & Gifts', breadcrumb: 'Donations & Gifts' }, component: () => import('@/app/librarian/acquisition/Acquisition.vue') },
    ],
  },
]
