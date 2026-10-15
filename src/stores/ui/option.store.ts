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
    sections: null as Option[] | null,
    patron_types: null as Option[] | null,

    url: 'option',
  }),

  actions: {
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
