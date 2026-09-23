interface Authorship {
  id: number
  name: string
  item_type_id: number
  created_at?: string
  updated_at?: string
  authorship_id: number
}

interface AuthorshipParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<AuthorshipParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

export const authorshipStore = defineStore('authorship', {
  state: () => ({
    authorships: null as Authorship[] | null,
    loading: false,
    error: null as string | null,
    params: { ...defaultParams } as AuthorshipParams,
  }),

  getters: {
    byItemType() {
      return (item_id: any) => this.authorships?.filter((i) => i.item_type_id === item_id)
    },
  },

  actions: {
    setAuthorships(data: Authorship[] | null) {
      this.authorships = data
    },

    setLoading(status: boolean) {
      this.loading = status
    },

    setError(message: string | null) {
      this.error = message
    },

    async fetch(forced = false) {
      if (!forced && this.authorships) return this.authorships

      this.setLoading(true)
      this.setError(null)

      try {
        const response = await get('authorship', { params: { ...this.params } })
        const data = response.data ?? []

        this.setAuthorships(data)
        return data
      } catch (error) {
        const message = error instanceof Error ? error.message : 'Unable to fetch authorship types.'

        this.setError(message)
        console.error('Error fetching authorships:', error)
        return []
      } finally {
        this.setLoading(false)
      }
    },

    async refresh() {
      Object.assign(this.params, defaultParams)
      return this.fetch(true)
    },
  },
})
