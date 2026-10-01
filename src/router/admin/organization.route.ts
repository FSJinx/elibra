export const adminOrganizationRoutes = [
  {
    path: 'campus',
    name: 'admin.campus',
    meta: {
      breadcrumb: 'Campus',
      permission: '',
      maintenance: false,
    },
    redirect: { name: 'admin.campus.list' },
    children: adminCampusRoutes,
  },
  {
    path: 'libraries',
    name: 'admin.libraries',
    meta: {
      breadcrumb: 'Libraries',
      permission: '',
      maintenance: false,
    },
    component: () => import('@/app/admin/libraries/Libraries.vue'),
    children: adminLibraryRoutes,
  },
  {
    path: 'vendors',
    name: 'admin.vendors',
    meta: {
      breadcrumb: 'Vendors',
      permission: '',
      maintenance: false,
    },
    component: () => import('@/app/admin/vendors/Vendors.vue'),
    children: adminVendorRoute,
  },
]
