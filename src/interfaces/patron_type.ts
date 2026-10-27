export interface PatronType {
  id: any
  key: string
  name: string
  description: string
  validity_period: string

  has_campus: string
  has_department: string
  has_program: string

  created_at: string
  updated_at: string
  deleted_at: string

  [key: string]: any
}
