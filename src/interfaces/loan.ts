export interface Loan {
  borrowed_at: string
  due_at: string
  returned_at: string

  status: string
  renewal_count: any
  fine_charged: any
  notes: string

  processed_by: any
  accession_id: any
  patron_id: any
  loan_mode_id: any
  return_received_by: any

  processor: Librarian
  accession: Accession
  patron: Patron
  loan_mode: LoanMode
  return_received: Librarian
}
