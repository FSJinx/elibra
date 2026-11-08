export interface Form extends Item {}

const auth = authStore()

export const emptyForm = (): StoreItem => ({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  language_id: '',
  keywords: [],
  electronic_file: null,
  cover_image: null,

  item_type_id: '',
  item_type_category_id: '',
  library_id: auth.user?.library?.id ?? '',

  publication_year: '',
  place_of_publication: '',
  publisher_id: null,

  released: false,

  edition: '',
  volume: '',
  issue: '',
  pages: '',
  isbn_issn: '',
  doi: '',
  department_id: '',
  authors: [],
})

export function getError(arr: any, field: string) {
  return arr?.[field]?.[0]
}
