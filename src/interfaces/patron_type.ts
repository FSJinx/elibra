export interface PatronType {
  id: any
  key: string
  name: string
  description: string
  validity_period: number

  has_campus: boolean
  has_department: boolean
  has_program: boolean

  created_at: string
  updated_at: string
  deleted_at: string

  [key: string]: any
}

export interface StorePatronType extends PatronType {}
