export interface Form extends Item {
  //   Author
  authors: Author[]
  year_published: ''
}

const auth = authStore()

export const emptyForm = (): Partial<Form> => ({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  language_id: '',
  keywords: [],
  electronic_file: null,

  item_type_id: '',
  item_type_category_id: '',
  library_id: auth.user?.library?.id ?? '',
  released: false,

  year_published: '',
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
