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

export const useCampusStore = defineStore('campus', {
  state: () => ({
    data: [] as Campus[],
    currentData: null as Campus | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  actions: {
    // =========== SETTERS ============
    pushData(data: Campus) {
      this.data?.push(data)
    },

    updateData(data: Campus) {
      this.data = this.data.filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Campus) {
      this.data = this.data.filter((i) => i.id !== data.id)
    },

    setData(data: Campus[]) {
      this.data = data
    },

    // =========== ACTIONS ============
    async fetch() {
      this.loading = true
      const res = await get('campus')
      this.setData(res.data)
      this.loading = false
    },

    async create(params: Partial<Campus>) {
      this.pop.load()
      const res = await post('campus', params)
      this.pushData(res.data)
      this.pop.success(res.message)

      return res
    },

    async update(params: Campus) {
      const res = await put(`campus/${params.id}`, params)
      this.updateData(res.data)
      return res
    },

    async remove(params: Campus) {
      const res = await del(`campus/${params?.id}`)
      this.removeData(params)
      return res
    },
  },
})
