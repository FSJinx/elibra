const url = 'library'

export const libraryStore = defineStore('library', {
  state: () => ({
    data: null as Library[] | null,
    currentData: null as Library | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    get() {
      return (id: any) => (this.data as Library[]).find((i) => i.id === id)
    },

    
  },

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

    setCurrentData(data: Library) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(forced = false) {
      if (this.data && !forced) return

      try {
        this.loading = true
        const res = await get(url)
        this.setData(res.data)
        return res
      } finally {
        this.loading = false
      }
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Library>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Library) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async remove(params: Library) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },

    async restore(params: Library) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
