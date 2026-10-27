export const loanModes = [
  {
    path: '',
    name: 'admin.loan_modes',
    meta: { breadcrumb: 'Loan Modes' },
    component: () => import('@/app/admin/loan_modes/LoanModes.vue'),
  },
  {
    path: ':id',
    name: 'admin.loan_modes.view',
    component: () => import('@/app/admin/loan_modes/ViewLoanMode.vue'),
  },
]
