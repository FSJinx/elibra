export const adminCampusRoutes = [
  {
    path: 'campus',
    name: 'admin.campus',
    meta: {
      breadcrumb: 'Campus',
      permission: '',
      maintenance: false,
    },
    redirect: { name: 'admin.campus.list' },
    children: [
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
        meta: { title: 'Campus Management' },
        redirect: { name: 'admin.campus.show.overview' },
        component: () => import('@/app/admin/campus/ViewCampus.vue'),
        beforeEnter: async (to: any) => {
          const campus = useCampusStore()
          const pop = usePopup()
          const breadcrumb = useBreadcrumbStore()

          if (campus.currentData !== null && campus.currentData.id == to.params?.id) return true

          pop.load()
          try {
            await campus.show(to.params.id)
            pop.unload()
          } catch (e: any) {
            const message = e?.response?.data?.message
            pop.error(message)
            return router.replace({ name: 'admin.campus' })
          } finally {
            const key = `${to.name as string}:${to.params.id}`
            breadcrumb.set(key, (campus.currentData as { name?: string } | null)?.name ?? '')
          }

          return true
        },
        children: [
          {
            path: '',
            name: 'admin.campus.show.overview',
            component: () => import('@/app/admin/campus/view/Overview.vue'),
          },
          {
            path: 'branches',
            name: 'admin.campus.show.branches',
            component: () => import('@/app/admin/campus/view/Branches.vue'),
          },
          {
            path: 'department',
            name: 'admin.campus.show.departments',
            component: () => import('@/app/admin/campus/view/Departments.vue'),
          },
        ],
      },
    ],
  },
]

export default adminCampusRoutes
