export interface AttendanceParams extends BaseParams {
  patron_id: any
  campus_id: any
  library_id: any
  section_id: any
}

export interface Attendance {
  id: any
  status: 'in' | 'out'
  patron_id: any
  library_id: any
  section_id: any

  patron: Patron | null
  section: Section | null
  library: Library | null

  [key: string]: any
}
