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
    patron_types: null as Option[] | null,

    url: 'option',
  }),

  actions: {
    async fetchCampuses() {
      const res = await get(`${this.url}/campuses`)
      this.campuses = res.data
    },

    async fetchSections(id?: any) {
      const res = await get(`${this.url}/sections`, { library_id: id })
      this.sections = res.data
    },

    async fetchPatronTypes() {
      const res = await get(`${this.url}/patron-types`)
      console.log(res.data)

      this.patron_types = res.data
    },
  },
})
