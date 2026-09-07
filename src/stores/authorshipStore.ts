interface Authorship {
  id: number
  name: string
  item_type_id: number
  created_at?: string
  updated_at?: string
  authorship_id: number
}

export const authorshipStore = defineStore('authorship', () => {
  const authorships = ref<Authorship[] | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  function setAuthorships(data: Authorship[] | null) {
    authorships.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  function setError(message: string | null) {
    error.value = message
  }

  return {
    authorships,
    loading,
    error,
    setAuthorships,
    setLoading,
    setError,
  }
})
