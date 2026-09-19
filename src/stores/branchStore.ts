export interface Branch {
  id: number
  name: string
  contact_info: string
  email: string
  email_verified_at: string
  opening_hour: string
  closing_hour: string
  logo_id: any
  branch_head_id: number
  campus_id: number
  created_at: string
  updated_at: string
  [key: string]: any
}

const url = 'branch'

export const useBranchStore = defineStore('branch', {
  state: () => ({
    data: [] as Branch[],
    currentData: null as Branch | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  actions: {
    // =========== SETTERS ============
    pushData(data: Branch) {
      this.data?.push(data)
    },

    updateData(data: Branch) {
      this.data = this.data.filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Branch) {
      this.data = this.data.filter((i) => i.id !== data.id)
    },

    setData(data: Branch[]) {
      this.data = data
    },

    // =========== ACTIONS ============
    async fetch() {
      this.loading = true
      const res = await get(url)
      this.setData(res.data?.data)
      this.loading = false
    },

    async create(params: Partial<Branch>) {
      this.pop.load()
      const res = await post(url, params)
      this.pushData(res.data)
      this.pop.success(res.message)

      return res
    },

    async update(params: Branch) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      return res
    },

    async remove(params: Branch) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },
  },
})
