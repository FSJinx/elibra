interface Query {
  query: string
  sort: string
  order: 'asc' | 'desc'
  page: number
  per_page: number
}

export function useCampus() {
  const store = campusStore()

  const query = reactive<Query>({
    query: '',
    sort: '',
    order: 'asc',
    page: 1,
    per_page: 10,
  })

  // --------- FETCH CAMPUS ---------
  async function getCampuses(params: Partial<Query> = {}) {
    if (params.query && params.query?.length > 0) console.log('search')

    if (store.campuses && store.campuses?.length > 0) return store.campuses

    store.setLoading(true)

    try {
      const res = await api.get('/campus/get', {
        params: {
          ...query,
          ...params,
        },
      })

      store.setCampuses(res.data.data)

      return res.data.data
    } catch (err) {
      return []
    } finally {
      store.setLoading(false)
    }
  }

  return {
    getCampuses,
  }
}
