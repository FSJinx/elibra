export interface LoanMode {
  id: any
  slug: string
  name: string
  description: string

  renewable: boolean
  charges_fine: boolean
  has_grace_period: boolean

  is_active: boolean
}

export interface StoreLoanMode extends Partial<LoanMode> {}
export interface UpdateLoanMode extends Partial<LoanMode> {}
