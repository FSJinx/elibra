export interface PatronTypeLoanPolicy {
  id: any
  can_reserve: string
  reservation_limit: string
  loan_period_days: string
  max_items: string
  max_renewals: string
  fine_per_due: string
  grace_period: string
  notes: string

  patron_type_id: any
  loan_mode_id: any
}
