export interface Section {
  id: any
  name: string

  library_id: any
  librarian_id: any

  library: Library
  // librarian: Librarian

  [key: string]: any
}
