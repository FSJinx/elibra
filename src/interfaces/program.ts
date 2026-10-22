export interface Program {
  id: any
  name: string
  code: string
  created_at: string
  updated_at: string
  deleted_at: string
  department_id: string

  department: Department
}
