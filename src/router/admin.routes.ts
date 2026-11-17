export const adminRoutes = [
  {
    path: 'settings/profile',
    name: 'admin.settings.profile',
    meta: { title: 'Profile', breadcrumb: 'Profile' },
    component: () => import('@/app/librarian/settings/Settings.vue'),
  },

  // Dashboard Routes
  ...adminOverviewRoutes,

  // Organization Routes
  ...adminOrganizationRoutes,

  // Users and Access Routes
  ...adminUsers,

  // Standard Routes
  ...adminStandard,

  // Authority Control Routes
  ...adminAuthorityRoute,

  // Policies Routes
  ...adminPolicy,
]
