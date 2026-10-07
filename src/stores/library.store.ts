const url = 'library'

export const useLibraryStore = defineStore('library', {
  state: () => ({
    data: null as Library[] | null,
    currentData: null as Library | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  actions: {
    // =========== SETTERS ============
    pushData(data: Library) {
      this.data?.push(data)
    },

    updateData(data: Library) {
      this.data = (this.data as Library[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Library) {
      this.data = (this.data as Library[]).filter((i) => i.id !== data.id)
    },

    setData(data: Library[]) {
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

    async create(params: Partial<Library>) {
      this.pop.load()
      const res = await post(url, params)
      this.pushData(res.data)
      this.pop.success(res.message)

      return res
    },

    async update(params: Library) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      return res
    },

    async remove(params: Library) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },
  },
})
