export interface LoanMode {
  id: any
  slug: string
  name: string
  description: string
}

export interface StoreLoanMode extends Partial<LoanMode> {}
export interface UpdateLoanMode extends Partial<LoanMode> {}
