export interface Item {
  id: any
  title: string
  subtitle: string
  description: string
  call_number: string
  electronic_file: File[] | null
  keywords: string[]

  edition: string
  isbn_issn: string
  copyright_year: string
  doi: string
  volume: string
  issue: string
  pages: string
  department_id: any

  released: boolean

  item_type_id: any
  item_type_category_id: any
  library_id: any
  language_id: any
  cover_media_id: any

  [key: string]: any

}
