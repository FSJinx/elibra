export const librarianAdministration = [
    {
        path: 'administration',
        name: 'librarian.administration',
        meta: { breadcrumb: 'Administration' },
        children: [
          { path: 'staff', name: 'librarian.administration.staff', meta: { title: 'Librarians / Staff', breadcrumb: 'Librarians / Staff' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'roles', name: 'librarian.administration.roles', meta: { title: 'Roles & Permissions', breadcrumb: 'Roles & Permissions' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'policies', name: 'librarian.administration.policies', meta: { title: 'Library Policies', breadcrumb: 'Library Policies' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'branches', name: 'librarian.administration.branches', meta: { title: 'Library Branches', breadcrumb: 'Library Branches' }, component: () => import('@/app/librarian/administration/Administration.vue') },
          { path: 'barcode', name: 'librarian.administration.barcode', meta: { title: 'Barcode Generator', breadcrumb: 'Barcode Generator' }, component: () => import('@/app/librarian/administration/Administration.vue') },
        ],
      }
]