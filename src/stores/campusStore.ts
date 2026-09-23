export interface Campus {
  id: any
  name: string
  code: string
  address: string
  heading: string
  status: string
  created_at: any
  updated_at: any
  deleted_at: any
}

const url = 'campus'

export const useCampusStore = defineStore('campus', {
  state: () => ({
    data: null as Campus[] | null,
    currentData: null as Campus | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    getCampus() {
      return (id: any) => (this.data as Campus[]).find((i) => i.id === id)
    },
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Campus) {
      this.data?.push(data)
    },

    updateData(data: Campus) {
      this.data = (this.data as Campus[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Campus) {
      this.data = (this.data as Campus[]).filter((i) => i.id !== data.id)
    },

    setData(data: Campus[]) {
      this.data = data
    },

    setCurrentData(data: Campus) {
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

    async create(params: Partial<Campus>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Campus) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      this.setCurrentData(res.data)
      return res
    },

    async remove(params: Campus) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },

    async restore(params: Campus) {
      const res = await patch(`${url}/${params?.id}`)
      this.pushData(res.data)
      return res
    },
  },
})
