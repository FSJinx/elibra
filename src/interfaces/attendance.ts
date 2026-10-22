export interface Attendance {
  id: any
  status: 'in' | 'out'
  patron_id: any
  library_id: any
  section_id: any

  patron: Patron
  section: Section
  library: Library

  [key: string]: any
}
