export const librarianStore = defineStore('librarian', {
  state: () => ({
    data: null as Librarian[] | null,
    currentData: null as Librarian | null,

    url: 'library',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params: LibrarianParams) {
      const res = await get(this.url)
      this.data = res.data.data
      return res
    },

    async show(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.currentData = res.data
      return res
    },

    async create(params: StoreLibrarian) {
      const res = await post(this.url, params)
      return res
    },

    async update(params: UpdateLibrarian) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async remove(params: Librarian) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },

    async restore(params: Librarian) {
      const res = await patch(`${this.url}/${params?.id}`)
      return res
    },
  },
})
