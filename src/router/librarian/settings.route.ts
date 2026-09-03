export const librarianSettings = [
    {
        path: 'settings',
        name: 'librarian.settings',
        meta: { breadcrumb: 'Settings' },
        children: [
          { path: 'account', name: 'librarian.settings.account', meta: { title: 'Account', breadcrumb: 'Account' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'profile', name: 'librarian.settings.profile', meta: { title: 'Profile', breadcrumb: 'Profile' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'preferences', name: 'librarian.settings.preferences', meta: { title: 'Preferences', breadcrumb: 'Preferences' }, component: () => import('@/app/librarian/settings/Settings.vue') },
          { path: 'system', name: 'librarian.settings.system', meta: { title: 'System Settings', breadcrumb: 'System Settings' }, component: () => import('@/app/librarian/settings/Settings.vue') },
        ],
      }
]