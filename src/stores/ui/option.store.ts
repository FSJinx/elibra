interface Option {
  id: any
  name: string

  [key: string]: any
}

export const optionStore = defineStore('option', {
  state: () => ({
    sections: null as Option[] | null,

    url: 'option',
  }),

  actions: {
    async fetchSections(id?: any) {
      const res = await get(`${this.url}/sections`, { library_id: id })
      this.sections = res.data
    },
  },
})
