interface LanguageParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<LanguageParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'language'

export const useLanguagesStore = defineStore('languages', {
  state: () => ({
    data: null as Language[] | null,
    currentData: null as Language | null,
    loading: false as true | false,
    params: defaultParams as LanguageParams,

    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    select() {
      return () => this.data?.sort((a, b) => a.name.localeCompare(b.name))
    },
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Language) {
      this.data?.push(data)
    },

    updateData(data: Language) {
      this.data = (this.data as Language[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Language) {
      this.data = (this.data as Language[]).filter((i) => i.id !== data.id)
    },

    setData(data: Language[]) {
      this.data = data
    },

    setCurrentData(data: Language) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(forced = false) {
      if (this.data && !forced) return

      this.loading = true
      const res = await get(url)
      this.setData(res.data)
      this.loading = false

      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Language>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Language) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Language) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Language) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
