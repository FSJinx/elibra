const pop = usePopup()

export const useAcquisitionLinesStore = defineStore('acquisition_lines', {
  state: () => ({
    url: 'librarian/acquisition-lines',

    fetchData: null as AcquisitionLine[] | null,
    searchData: null as AcquisitionLine[] | null,
    currentData: null as AcquisitionLine | null,
    loading: false as boolean,
  }),

  getters: {
    data(): AcquisitionLine[] | null {
      return this.searchData ?? this.fetchData ?? null
    },
  },

  actions: {
    setFetchData(data: AcquisitionLine[] | null) {
      this.fetchData = data
    },

    setSearchData(data: AcquisitionLine[] | null) {
      this.searchData = data
    },

    setCurrentData(data: AcquisitionLine | null) {
      this.currentData = data
    },

    async fetch(id: any) {
      const res = await get(this.url, { id: id })
      this.setFetchData(res.data)
    },

    async create(params: Partial<AcquisitionLine>) {
      const res = await post(this.url, params)
      return res
    },

    async read(id: any) {
      pop.load()

      try {
        const res = await get(`${this.url}/show/${id}`)

        console.log(res.data)
      } catch (e) {
      } finally {
        pop.unload()
      }
    },
  },
})
