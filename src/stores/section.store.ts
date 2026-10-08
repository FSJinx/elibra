interface SectionParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<SectionParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

const url = 'section'

export const sectionStore = defineStore('section', {
  state: () => ({
    data: null as Section[] | null,
    currentData: null as Section | null,
    loading: false as true | false,
    params: defaultParams as SectionParams,

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
    pushData(data: Section) {
      this.data?.push(data)
    },

    updateData(data: Section) {
      this.data = (this.data as Section[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Section) {
      this.data = (this.data as Section[]).filter((i) => i.id !== data.id)
    },

    setData(data: Section[]) {
      this.data = data
    },

    setCurrentData(data: Section) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(id?: any, forced = false) {
      if (this.data && !forced) return

      this.loading = true
      const res = await get(url, { id: id })
      this.setData(res.data)
      this.loading = false

      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Section>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Section) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async destory(params: Section) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)

      return res
    },

    async restore(params: Section) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
