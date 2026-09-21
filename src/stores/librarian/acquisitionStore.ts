export interface Acquisition {
  id?: any
  acquisition_id?: string
  dealer: string
  acquisition_mode: string
  acquisition_date: string
  remarks: string

  created_at?: string
  updated_at?: string
}

const pop = usePopup()

export const useAcquisitionStore = defineStore('acquisition', {
  state: () => ({
    url: 'librarian/acquisition',

    fetchData: null as Acquisition[] | null,
    searchData: null as Acquisition[] | null,
    currentData: null as Acquisition | null,
    loading: false as boolean,
    auth: authStore(),
  }),

  getters: {
    data(): Acquisition[] | null {
      return this.searchData !== null ? this.searchData : this.fetchData
    },
  },

  actions: {
    // ========== SETTERS ==========
    setFetchData(data: Acquisition[]) {
      this.fetchData = data
    },

    setSearchData(data: Acquisition[]) {
      this.searchData = data
    },

    setCurrentData(data: Acquisition) {
      this.currentData = data
    },

    // ========== API CALLS ==========
    async fetch() {
      this.loading = true

      try {
        const res = await api.get(this.url)
        this.fetchData = res.data?.data?.data ?? []
        return this.fetchData
      } catch (error) {
        this.fetchData = []
        return []
      } finally {
        this.loading = false
      }
    },

    async search() {},

    async create(params: Partial<Acquisition>): Promise<{ success: boolean; errors?: Record<string, string[]> }> {
      pop.load()

      try {
        const res = await api.post('librarian/acquisition', {
          ...params,
          receiver_user_id: this.auth?.user?.id,
        })

        const acquisition = res.data?.data?.data

        this.fetchData?.push(acquisition)

        pop.success('Acquisition created successfully!')
        return { success: true }
      } catch (error: any) {
        const res = error.response?.data

        return {
          success: false,
          errors: res?.errors ?? (res?.message ? { general: [res.message] } : {}),
        }
      }
    },

    async read(id: any) {
      const res = await get(`${this.url}/${id}`)

      this.setCurrentData(res.data as Acquisition)
    },

    async update() {},
  },
})
