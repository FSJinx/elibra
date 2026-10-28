export interface PatronTypeLoanPolicy {
  id: any
  loan_period_days: any
  max_items: any
  max_renewals: any
  fine_per_due: any
  grace_period: any
  can_reserve: boolean
  reservation_limit: any
  notes: any

  patron_type_id: any
  loan_mode_id: any

  patron_type: Partial<PatronType>
}

export interface StorePatronTypeLoanPolicy extends Partial<PatronTypeLoanPolicy> {
  loan_period_unit?: string
  grace_period_unit?: string
}
export interface UpdatePatronTypeLoanPolicy extends Partial<PatronTypeLoanPolicy> {}
