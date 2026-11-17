export interface SectionParams extends BaseParams {
  library_id: any
}

export interface Section {
  id: any
  name: string

  library_id: any
  librarian_id: any

  library: Library | null
  librarian: Librarian | null

  created_at: string
  updated_at: string
  deleted_at: string

  [key: string]: any
}
