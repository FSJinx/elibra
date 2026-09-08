export function useAuthorship() {
  const store = authorshipStore()

  async function getAuthorships(forced = false) {
    if (!forced && store.authorships) {
      return store.authorships
    }

    store.setLoading(true)
    store.setError(null)

    try {
      const response = await api.get('authorship')
      const authorships = response.data?.data ?? []

      store.setAuthorships(authorships)
      return authorships
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Unable to fetch authorship types.'

      store.setError(message)
      console.error('Error fetching authorships:', error)
      return []
    } finally {
      store.setLoading(false)
    }
  }

  function refresh() {
    return getAuthorships(true)
  }

  return {
    authorships: store.authorships,
    loading: store.loading,
    error: store.error,
    getAuthorships,
    refresh,
  }
}
