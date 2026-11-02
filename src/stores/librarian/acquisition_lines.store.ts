const pop = usePopup()

export const acquisitionLinesStore = defineStore('acquisition_lines', {
  state: () => ({
    data: null as AcquisitionLine[] | null,
    currentData: null as AcquisitionLine | null,

    url: 'acquisition-lines',
  }),

  actions: {
    setCurrentData(data: AcquisitionLine | null) {
      this.currentData = data
    },

    async fetch(id: any) {
      const res = await get(this.url, { id: id })
      this.data = res.data?.data
      return res
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

    async update(l: AcquisitionLine) {
      const res = await put(`${this.url}/${l.id}`, l)
      return res
    },

    async destroy(l: AcquisitionLine) {
      const res = await del(`${this.url}/${l.id}`)
      return res
    },
  },
})
