export interface Department {
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

const url = 'department'

export const useDepartmentStore = defineStore('department', {
  state: () => ({
    data: null as Department[] | null,
    currentData: null as Department | null,
    loading: false as true | false,
    pop: usePopup(),
    auth: authStore(),
  }),

  getters: {
    getDepartment() {
      return (id: any) => (this.data as Department[]).find((i) => i.id === id)
    },
  },

  actions: {
    // =========== SETTERS ============
    pushData(data: Department) {
      this.data?.push(data)
    },

    updateData(data: Department) {
      this.data = (this.data as Department[]).filter((i) => i.id !== data.id)
      nextTick(() => this.data?.push(data))
    },

    removeData(data: Department) {
      this.data = (this.data as Department[]).filter((i) => i.id !== data.id)
    },

    setData(data: Department[]) {
      this.data = data
    },

    setCurrentData(data: Department) {
      this.currentData = data
    },

    // =========== ACTIONS ============
    async fetch(params = {}, forced = false) {
      if (this.data && !forced) return

      this.loading = true
      const res = await get(url, params)
      this.setData(res.data)
      this.loading = false

      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)
      this.setCurrentData(res?.data)
      return res
    },

    async create(params: Partial<Department>) {
      const res = await post(url, params)
      this.pushData(res.data)

      return res
    },

    async update(params: Department) {
      const res = await put(`${url}/${params.id}`, params)
      this.updateData(res.data)
      return res
    },

    async remove(params: Department) {
      const res = await del(`${url}/${params?.id}`)
      this.removeData(params)
      return res
    },
  },
})
