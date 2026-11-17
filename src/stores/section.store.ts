const defaultParams: Readonly<SectionParams> = {
  query: '',
  sort: '',
  page: 1,
  per_page: '25',
  order: 'asc',

  library_id: '',
}

export const sectionStore = defineStore('section', {
  state: () => ({
    data: null as Section[] | null,
    currentData: null as Section | null,

    url: 'section',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params: Partial<SectionParams>) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: Partial<Section>) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: Section) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async destroy(params: Section) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async destory(params: Section) {
      return this.destroy(params)
    },

    async restore(params: Section) {
      const res = await patch(`${this.url}/${params?.id}/restore`)
      return res
    },
  },
})
