export interface AcquisitionLines {
  id?: any
  quantity: number | null
  unit_price: number | null
  discount: number | null
  net_price: number | null
  [key: string]: any
}

const pop = usePopup()

export const useAcquisitionLinesStore = defineStore('acquisition_lines', {
  state: () => ({
    url: 'librarian/acquisition-lines',

    fetchData: null as AcquisitionLines[] | null,
    searchData: null as AcquisitionLines[] | null,
    currentData: null as AcquisitionLines | null,
    loading: false as boolean,
  }),

  getters: {
    data(): AcquisitionLines[] | null {
      return this.searchData ?? this.fetchData ?? null
    },
  },

  actions: {
    setFetchData(data: AcquisitionLines[] | null) {
      this.fetchData = data
    },

    setSearchData(data: AcquisitionLines[] | null) {
      this.searchData = data
    },

    setCurrentData(data: AcquisitionLines | null) {
      this.currentData = data
    },

    async fetch(id: any) {
      const res = await get(`${this.url}/${id}`)
      this.setFetchData(res.data)
    },

    async read(id: any) {
      pop.load()

      try {
        const res = await get(`${this.url}/${id}`)

        console.log(res.data)
      } catch (e) {
      } finally {
        pop.unload()
      }
    },
  },
})
