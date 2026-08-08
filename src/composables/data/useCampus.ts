interface Params {
  query: string
  sort: string
  order: 'asc' | 'desc'
  page: number
  per_page: number
}

const defaultParams: Readonly<Params> = {
  query: '',
  sort: '',
  order: 'asc',
  page: 1,
  per_page: 10,
}

// ========== Api Route ==========
const url = {
  get: 'campus/get',
  post: 'campus/post',
}

export function useCampus() {
  const store = campusStore()
  const params = reactive<Params>({ ...defaultParams })

  // --------- FETCH CAMPUS ---------
  async function getCampuses(forced = false) {
    // Returns the cached data if fetch is not forced
    if (!forced && store.campuses && store.campuses.length) {
      return store.campuses
    }

    store.setLoading(true)

    try {
      const res = await api.get(url.get, {
        params: {
          ...params,
        },
      })

      store.setCampuses(res.data.data)
    } catch (err) {
      return []
    } finally {
      store.setLoading(false)
    }
  }

  // --------- FETCH CAMPUS ---------
  async function refresh() {
    Object.assign(params, defaultParams)
  }

  // ---------- SEARCHING FUNCTION -----------
  watchDebounced(
    () => ({ ...params }),
    (p) => {
      getCampuses(true)
    },
    // {
    //   debounce: 200,
    // },
  )

  return {
    params,
    getCampuses,
    refresh,
  }
}
