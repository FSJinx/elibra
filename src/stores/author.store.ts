export const useAuthorStore = defineStore('authors', {
  state: () => ({
    data: null as Author[] | null,
    currentData: null as Author | null,
    loading: false as true | false,

    url: 'authors',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params: AuthorParams) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async create(payload: StoreAuthor) {
      const res = await post(this.url, payload)
      return res
    },

    async update(params: Author) {
      const res = await put(`${this.url}/${params.id}`, params)
      return res
    },

    async remove(params: Author) {
      const res = await del(`${this.url}/${params?.id}`)
      return res
    },
  },
})
