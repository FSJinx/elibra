export interface Section {
  id: any
  name: string

  library_id: any
  librarian_id: any

  library: Library
  // librarian: Librarian

  created_at: string
  updated_at: string
  deleted_at: string

  [key: string]: any
}
