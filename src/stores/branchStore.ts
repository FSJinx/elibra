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
  campus_id: any
  created_at: string
  updated_at: string
  [key: string]: any
}

const url = 'branch'

export const useBranchStore = defineStore('branch', {
  state: () => ({
    data: null as Branch[] | null,
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
      this.data = (this.data as Branch[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Branch) {
      this.data = (this.data as Branch[]).filter((i) => i.id !== data.id)
    },

    setData(data: Branch[]) {
      this.data = data
    },

    // =========== ACTIONS ============
    async fetch(params = {}, forced = false) {
      try {
        if (this.data && !forced) return

        this.loading = true
        const res = await get(url, params)
        this.setData(res.data?.data)

        return res
      } finally {
        this.loading = false
      }
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
