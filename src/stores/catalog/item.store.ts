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

export const itemStore = defineStore('item', {
  state: () => ({
    data: null as Item[] | null,
    currentData: null as Item | null,

    url: 'items',
  }),

  actions: {
    async fetch(params: Partial<ItemParams>) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreItem) {
      const formData = new FormData()
      Object.entries(params).forEach(([key, value]) => appendFormData(formData, key, value))

      const res = await post(this.url, formData)
      return res
    },

    async update(id: string | number, params: UpdateItem) {
      const formData = new FormData()
      Object.entries(params).forEach(([key, value]) => appendFormData(formData, key, value))
      formData.append('_method', 'PUT')

      const res = await post(`${this.url}/${id}`, formData)
      this.currentData = res.data
      return res
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
