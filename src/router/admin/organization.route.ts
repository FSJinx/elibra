export const adminOrganizationRoutes = [
  {
    path: 'campus',
    name: 'admin.campus',
    meta: {
      breadcrumb: 'Campus',
      title: 'Organizations',
      permission: '',
      maintenance: false,
    },
    redirect: { name: 'admin.campus.list' },
    children: adminCampusRoutes,
  },
  {
    path: 'libraries',
    redirect: { name: 'admin.libraries' },
    meta: {
      breadcrumb: 'Libraries',
      title: 'Organizations',
      permission: '',
      maintenance: false,
    },
    children: adminLibraryRoutes,
  },
  {
    path: 'vendors',
    name: 'admin.vendors',
    meta: {
      breadcrumb: 'Vendors',
      title: 'Organizations',
      permission: '',
      maintenance: false,
    },
    component: () => import('@/app/admin/vendors/Vendors.vue'),
    children: adminVendorRoute,
  },
]
