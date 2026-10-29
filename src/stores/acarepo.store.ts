export const acaRepoStore = defineStore('acarepo', {
  state: () => ({
    data: null as AcaRepo[] | null,
    currentData: null as AcaRepo | null,
    loading: false,

    url: 'acarepo',
  }),

  actions: {
    // =========== ACTIONS ============
    async fetch(params?: Partial<AcaRepoParams>) {
      this.loading = true

      try {
        const res = await get(this.url, params)
        this.data = res.data.data
        return res
      } finally {
        this.loading = false
      }
    },

    async show(id: number) {
      const res = await get(`${this.url}/${id}`)
      return res
    },

    async create(payload: Partial<StoreAcaRepo>) {
      const res = await post(this.url, payload, { headers: { 'Content-Type': 'multipart/form-data' } })
      return res
    },

    async update(payload: UpdateAcaRepo) {
      const res = await put(`${this.url}/${payload.id}`, payload, { headers: { 'Content-Type': 'multipart/form-data' } })
      return res
    },

    async remove(payload: AcaRepo) {
      const res = await del(`${this.url}/${payload.id}`)
      return res
    },

    // =========== ADDINATIONAL ACTIONS ============
    async approve(payload: AcaRepo) {
      const res = await put(`${this.url}/${payload.id}/approve`, payload)
      return res
    },

    async reject(payload: UpdateAcaRepo) {
      const res = await put(`${this.url}/${payload.id}/reject`, payload)
      return res
    },
  },
})
