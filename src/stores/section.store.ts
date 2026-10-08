interface SectionParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<SectionParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'section'

export const sectionStore = defineStore('section', {
  state: () => ({
    data: null as Section[] | null,
    deletedData: null as Section[] | null,
    currentData: null as Section | null,
    loading: false as true | false,
    loadedLibraryId: null as number | null,
    params: defaultParams as SectionParams,

    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    select() {
      return () => this.data?.slice().sort((a, b) => a.name.localeCompare(b.name))
    },
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Section) {
      this.data = [...(this.data ?? []), data]
    },

    updateData(data: Section) {
      this.data = (this.data ?? []).map((section) => section.id === data.id ? data : section)
    },

    removeData(data: Section) {
      this.data = (this.data ?? []).filter((section) => section.id !== data.id)
    },

    setData(data: Section[]) {
      this.data = data
    },

    setDeletedData(data: Section[]) {
      this.deletedData = data
    },

    setCurrentData(data: Section) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(libraryId?: number, forced = false) {
      if (this.data && this.loadedLibraryId === (libraryId ?? null) && !forced) return

      this.loading = true
      try {
        const res = await get(url, { library_id: libraryId })
        this.setData(res.data)
        this.loadedLibraryId = libraryId ?? null
        return res
      } finally {
        this.loading = false
      }
    },

    async fetchDeleted(libraryId?: number) {
      const res = await get(`${url}/deleted`, { library_id: libraryId })
      this.setDeletedData(res.data)
      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Section>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Section) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destroy(params: Section) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async destory(params: Section) {
      return this.destroy(params)
    },

    async restore(params: Section) {
      const res = await patch(`${url}/${params?.id}/restore`)
      this.deletedData = (this.deletedData ?? []).filter((section) => section.id !== params.id)
      this.pushData(res.data)
      return res
    },
  },
})
