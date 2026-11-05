export interface ItemParams extends BaseParams {
  item_type_id: any
  item_type_category_id: any
}
export interface Item {
  id: any
  title: string
  subtitle: string
  description: string
  call_number: string
  electronic_file: File | string | null
  keywords: string[]

  edition: string
  isbn_issn: string
  copyright_year: string
  doi: string
  volume: string
  issue: string
  pages: string
  department_id: any

  publication_year: string
  place_of_publication: string

  released: boolean

  item_type_id: any
  item_type_category_id: any
  library_id: any
  language_id: any
  cover_media_id: any
  publisher_id: any

  cover_media?: {
    file_path: string
    file_name: string
  } | null

  created_at: string
  updated_at: string
  deleted_at: string

  // Relationships

  authors: Author[]
  publisher: Publisher

  [key: string]: any
}

export interface StoreItem extends Partial<Item> {}
export interface UpdateItem extends Partial<Item> {}
