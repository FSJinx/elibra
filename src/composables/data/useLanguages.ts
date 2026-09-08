interface Params {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<Params> = {
  page: 1,
  per_page: 10,
  order: 'asc',
  sort: '',
}

const url = {
  get: 'languages',
}

export function useLanguages() {
  const store = languagesStore()
  const params = reactive<Params>({ ...defaultParams })

  async function getLanguages(forced = false) {
    if (!forced && store.languages?.length) {
      return store.languages
    }

    store.setLoading(true)

    try {
      const response = await api.get(url.get, {
        params: { ...params },
      })

      store.setLanguages(response.data?.data ?? [])
      return store.languages
    } catch (error) {
      console.error('Error fetching languages:', error)
      return []
    } finally {
      store.setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return getLanguages(true)
  }

  watchDebounced(
    () => ({ ...params }),
    () => getLanguages(true),
  )

  return { params, getLanguages, refresh }
}
