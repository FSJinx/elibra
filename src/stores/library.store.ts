export const libraryStore = defineStore('library', {
  state: () => ({
    data: null as Library[] | null,
    currentData: null as Library | null,

    url: 'library',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch() {
      const res = await get(this.url)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreLibrary) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: UpdateLibrary) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async remove(params: Library) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async restore(params: Library) {
      const res = await patch(`${this.url}/${params?.id}`)
      return res
    },
  },
})
