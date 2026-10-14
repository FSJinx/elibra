export const adminLibraryRoutes = [
  {
    path: '',
    name: 'admin.libraries',
    component: () => import('@/app/admin/libraries/Libraries.vue'),
  },
  {
    path: ':id',
    name: 'admin.libraries.view',
    component: () => import('@/app/admin/libraries/ViewLibrary.vue'),
    beforeEnter: async (to: any) => {
      const library = libraryStore()
      const crumb = useBreadcrumbStore()
      const pop = usePopup()

      pop.load()
      await library.show(to.params.id)
      pop.unload()

      crumb.set(`${to.name}:${to.params.id}`, library.currentData?.name as string)

      return true
    },
    children: [
      {
        path: '',
        name: 'admin.libraries.view.overview',
        component: () => import('@/app/admin/libraries/view/Overview.vue'),
      },
      {
        path: 'sections',
        name: 'admin.libraries.view.sections',
        component: () => import('@/app/admin/libraries/view/Sections.vue'),
      },
    ],
  },
]
