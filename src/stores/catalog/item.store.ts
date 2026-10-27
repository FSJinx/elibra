export interface ItemParams extends BaseParams {
  item_type_id: any
  item_type_category_id: any
}

function appendFormData(formData: FormData, key: string, value: unknown): void {
  if (value == null) return

  if (typeof value === 'boolean') {
    formData.append(key, value ? '1' : '0')
    return
  }

  if (value instanceof File) {
    formData.append(key, value)
    return
  }

  if (Array.isArray(value)) {
    value.forEach((item, index) => appendFormData(formData, `${key}[${index}]`, item))
    return
  }

  if (typeof value === 'object') {
    Object.entries(value).forEach(([nestedKey, nestedValue]) => appendFormData(formData, `${key}[${nestedKey}]`, nestedValue))
    return
  }

  formData.append(key, String(value))
}

export const useItemStore = defineStore('item', {
  state: () => ({
    url: '/items',
  }),

  actions: {
    async fetch(params: Partial<ItemParams>) {
      const res = await get(this.url, params)
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      return res
    },

    async create(params: Partial<Item>) {
      const formData = new FormData()
      Object.entries(params).forEach(([key, value]) => appendFormData(formData, key, value))

      const res = await post(this.url, formData)
      return res
    },

    async update(id: string | number, params: Partial<Item>) {
      const formData = new FormData()
      const fields = ['title', 'subtitle', 'description', 'call_number', 'electronic_file', 'cover_image', 'keywords', 'edition', 'isbn_issn', 'doi', 'volume', 'issue', 'pages', 'department_id', 'released', 'item_type_id', 'item_type_category_id', 'language_id', 'authors']

      fields.forEach((field) => {
        const value = params[field]
        if ((field === 'electronic_file' || field === 'cover_image') && !(value instanceof File)) {
          return
        }
        if (field === 'authors' && Array.isArray(value) && value.length === 0) {
          formData.append('authors', '[]')
        } else if (field === 'keywords' && Array.isArray(value) && value.length === 0) {
          formData.append('keywords', '[]')
        } else if (value !== undefined) {
          appendFormData(formData, field, value)
        }
      })
      formData.append('_method', 'PUT')

      return await post(`${this.url}/${id}`, formData)
    },

    /**
     *
     * @description Relational fetches
     *
     */

    async fetchResource(resource: string, id: any) {
      const res = await get(`${this.url}/${id}/${resource}`)
      return res
    },
  },
})
