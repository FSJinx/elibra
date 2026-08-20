export const adminCampusRoutes = [
  {
    path: '',
    name: 'admin.campus.list',
    meta: {
      title: 'Campus Management',
      description: 'Manage campuses in the Isabela State University.',
    },
    component: () => import('@/app/admin/campus/Campus.vue'),
  },
  {
    path: ':id',
    name: 'admin.campus.show',
    redirect: { name: 'admin.campus.show.overview' },
    meta: { breadcrumb: 'Campus Information' },
    component: () => import('@/app/admin/campus/ViewCampus.vue'),
    children: [
      {
        path: '',
        meta: { breadcrumb: 'Overview' },
        name: 'admin.campus.show.overview',
        component: () => import('@/app/admin/campus/CampusInfo.vue'),
      },
      {
        path: 'departments',
        meta: { breadcrumb: 'Departments' },
        name: 'admin.campus.show.departments',
        component: () => import('@/app/admin/campus/CampusDepartments.vue'),
      },
      {
        path: 'branches',
        meta: { breadcrumb: 'Branches' },
        name: 'admin.campus.show.branches',
        component: () => import('@/app/admin/campus/CampusBranches.vue'),
      },
    ],
  },
]

export default adminCampusRoutes
