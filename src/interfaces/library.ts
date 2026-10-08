export interface Library {
  id: number
  name: string
  contact_info: string
  email: string
  email_verified_at: string
  opening_hour: string
  closing_hour: string
  logo_id: any
  library_head_id: number
  campus_id: any
  created_at: string
  updated_at: string

  campus: Campus

  [key: string]: any
}
