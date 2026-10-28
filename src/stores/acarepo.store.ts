type AcaRepoResponse = Omit<AcaRepo, 'abstract'> & { description: string }

function fromResponse({ description, ...record }: AcaRepoResponse): AcaRepo {
  return { ...record, abstract: description }
}

function toRequest({ abstract, ...payload }: AcaRepoPayload) {
  return { ...payload, description: abstract }
}

export const acaRepoStore = defineStore('acarepo', {
  state: () => ({
    data: null as AcaRepo[] | null,
    currentData: null as AcaRepo | null,
    loading: false,

    url: 'acarepo',
  }),

  actions: {
    // =========== SETTERS ============
    setData(data: AcaRepo[]) {
      this.data = data
    },

    setCurrentData(data: AcaRepo) {
      this.currentData = data
    },

    pushData(data: AcaRepo) {
      this.data = [...(this.data ?? []), data]
    },

    updateData(data: AcaRepo) {
      this.data = (this.data ?? []).map((item) => (item.id === data.id ? data : item))
    },

    removeData(id: number) {
      this.data = (this.data ?? []).filter((item) => item.id !== id)
      if (this.currentData?.id === id) this.currentData = null
    },

    // =========== ACTIONS ============
    async fetch() {
      this.loading = true

      try {
        const res = await get(this.url)
        const data = res.data.map(fromResponse)
        this.setData(data)

        return { ...res, data }
      } finally {
        this.loading = false
      }
    },

    async show(id: number) {
      const res = await get(`${this.url}/${id}`)
      const data = fromResponse(res.data)
      this.setCurrentData(data)

      return { ...res, data }
    },

    async create(payload: AcaRepoPayload) {
      const res = await post(this.url, toRequest(payload))
      const data = fromResponse(res.data)
      this.pushData(data)
      this.setCurrentData(data)

      return { ...res, data }
    },

    async update(id: number, payload: AcaRepoPayload) {
      const res = await put(`${this.url}/${id}`, toRequest(payload))
      const data = fromResponse(res.data)
      this.updateData(data)
      this.setCurrentData(data)

      return { ...res, data }
    },

    async remove(id: number) {
      return this.destroy(id)
    },

    async destroy(id: number) {
      const res = await del(`${this.url}/${id}`)
      this.removeData(id)

      return res
    },
  },
})
