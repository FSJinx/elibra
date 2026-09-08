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

export const authorshipStore = defineStore('authorship', () => {
  const authorships = ref<Authorship[] | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)
  const params = reactive<AuthorshipParams>({ ...defaultParams })

  function setAuthorships(data: Authorship[] | null) {
    authorships.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  function setError(message: string | null) {
    error.value = message
  }

  async function fetch(forced = false) {
    if (!forced && authorships.value) return authorships.value

    setLoading(true)
    setError(null)

    try {
      const response = await get('authorship', { params: { ...params } })
      const data = response.data?.data ?? []

      setAuthorships(data)
      return data
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Unable to fetch authorship types.'

      setError(message)
      console.error('Error fetching authorships:', error)
      return []
    } finally {
      setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return fetch(true)
  }

  return {
    authorships,
    loading,
    error,
    params,
    setAuthorships,
    setLoading,
    setError,
    fetch,
    refresh,
  }
})
