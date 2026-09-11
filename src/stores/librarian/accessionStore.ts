export interface Accession {
  [key: string]: any
}

const pop = usePopup()

export const useAccessionStore = defineStore('accession', {
  state: () => ({
    url: 'librarian/accession',

    fetchData: null as Accession[] | null,
    searchData: null as Accession[] | null,
    currentData: null as Accession | null,
    loading: false as boolean,
  }),

  getters: {
    data(): Accession[] | null {
      return this.searchData ?? this.fetchData ?? null
    },
  },

  actions: {
    setFetchData(data: Accession[] | null) {
      this.fetchData = data
    },

    setSearchData(data: Accession[] | null) {
      this.searchData = data
    },

    setCurrentData(data: Accession | null) {
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
