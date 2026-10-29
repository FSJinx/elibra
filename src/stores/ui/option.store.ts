type Key = Exclude<keyof ReturnType<typeof state>, 'url'>

const state = () => ({
  // ======> ORGANIZATIONS
  campuses: null as Partial<Campus>[] | null,
  departments: null as Partial<Department>[] | null,
  libraries: null as Partial<Library>[] | null,
  programs: null as Partial<Program>[] | null,
  sections: null as Partial<Section>[] | null,

  // ======> STANDARDS
  authorships: null as Partial<Authorship>[] | null,
  categories: null as Partial<ItemCategory>[] | null,
  item_types: null as Partial<ItemType>[] | null,
  patron_types: null as Partial<PatronType>[] | null,
  loan_modes: null as Partial<LoanMode>[] | null,

  // ======> AUTHORITY CONTROL
  languages: null as Partial<Language>[] | null,

  url: 'option',
})

export const optionStore = defineStore('option', {
  state,

  actions: {
    async fetch(key: Key, forced = false) {
      if (this[key] && !forced) return
      this[key] = (await get(`${this.url}/${key}`)).data
    },

    async preload() {
      await Promise.all(
        Object.keys(state())
          .filter((k) => k !== 'url')
          .map((k) => this.fetch(k as Key)),
      )
    },
  },
})
