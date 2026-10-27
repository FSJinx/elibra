export interface PatronTypeLoanPolicy {
  id: any
  can_reserve: boolean
  reservation_limit: string
  loan_period_days: string
  max_items: string
  max_renewals: string
  fine_per_due: string
  grace_period: string
  notes: string

  patron_type_id: any
  loan_mode_id: any

  patron_type: Partial<PatronType>
}

export interface StorePatronTypeLoanPolicy extends Partial<PatronTypeLoanPolicy> {}
export interface UpdatePatronTypeLoanPolicy extends Partial<PatronTypeLoanPolicy> {}
