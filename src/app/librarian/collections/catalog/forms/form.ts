type Author = {
  id?: number
  last_name: string
  first_name: string
  middle_name: string
  authorship_id?: number | null
}

export interface BaseField {
  // Basic Information
  title: string
  subtitle: string | null
  description: string | null
  call_number: string
  publication_year: string
  electronic_file: File[] | null
  keywords: string[]
}

export interface ClassficationField {
  // Classification
  item_type_category_id: string
  branch_id: string | number | null
  language_id: string
}

export interface AuthorField {
  // Pivot
  authors: Author[]
}

export interface BookField {
  // Book Fields
  edition: string
  isbn_issn: string
  copyright_year: string
  doi: string
}

export interface AcademicField {}

export interface SerialField {}
