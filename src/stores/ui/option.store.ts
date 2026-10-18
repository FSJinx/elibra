interface Option {
  id: any
  key?: string
  code?: string
  slug?: string
  name: string

  [key: string]: any
}

export const optionStore = defineStore('option', {
  state: () => ({
    campuses: null as Option[] | null,
    sections: null as Option[] | null,
    patron_types: null as Partial<PatronType>[] | null,

    url: 'option',
  }),

  actions: {
    async fetchCampuses() {
      if (this.campuses) return

      const res = await get(`${this.url}/campuses`)
      this.campuses = res.data
    },

    async fetchSections(id?: any) {
      if (this.sections) return

      const res = await get(`${this.url}/sections`, { library_id: id })
      this.sections = res.data
    },

    async fetchPatronTypes() {
      if (this.patron_types) return

      const res = await get(`${this.url}/patron-types`)
      this.patron_types = res.data
    },
  },
})
