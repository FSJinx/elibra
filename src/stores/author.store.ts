const url = 'authors'

export const useAuthorStore = defineStore('authors', {
  state: () => ({
    data: null as Author[] | null,
    currentData: null as Author | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  actions: {
    // =========== SETTERS ============
    pushData(data: Author) {
      this.data?.push(data)
    },

    updateData(data: Author) {
      this.data = (this.data as Author[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Author) {
      this.data = (this.data as Author[]).filter((i) => i.id !== data.id)
    },

    setData(data: Author[]) {
      this.data = data
    },

    // =========== ACTIONS ============
    async fetch(params = {}, forced = false) {
      try {
        if (this.data && !forced) return

        this.loading = true
        const res = await get(url, params)
        this.setData(res.data?.data)

        return res
      } finally {
        this.loading = false
      }
    },

    async create(params: Partial<Author>) {
      this.pop.load()
      const res = await post(url, params)
      this.pushData(res.data)
      this.pop.success(res.message)

      return res
    },

    async update(params: Author) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      return res
    },

    async remove(params: Author) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },
  },
})
