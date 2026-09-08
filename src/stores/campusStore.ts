export interface Campus {
  id: number | null
  name: string | null
  code: string | null
  address: string | null
  heading: string | null
  status: string | null
  created_at: string | null
  updated_at: string | null
}

export interface CampusParams {
  query: string
  sort: string
  order: 'asc' | 'desc'
  status: string
  page: number
  per_page: number
}

const defaultParams: Readonly<CampusParams> = {
  query: '',
  sort: '',
  order: 'asc',
  status: '',
  page: 1,
  per_page: 10,
}

export const useCampusStore = defineStore('campus', () => {
  const campuses = ref<Campus[] | null>(null)
  const currentCampus = ref<Campus | null>(null)
  const loading = ref(false)
  const params = reactive<CampusParams>({ ...defaultParams })

  function setCampuses(data: Campus[] | null) {
    campuses.value = data
  }

  function setCurrentCampus(data: Campus | null) {
    currentCampus.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  async function fetch(forced = false) {
    if (!forced && campuses.value?.length) {
      return campuses.value
    }

    setLoading(true)

    try {
      const response = await api.get('campus/get', {
        params: { ...params },
      })
      const data = response.data.data

      setCampuses(data)
      return data
    } catch (error) {
      console.error('Failed to fetch campuses:', error)
      return []
    } finally {
      setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return fetch(true)
  }

  async function deleteCampus(campus: Campus) {
    await api.delete(`campus/delete/${campus.id}`)
    return fetch(true)
  }

  watchDebounced(
    () => ({ ...params }),
    () => fetch(true),
    { debounce: 300 },
  )

  return {
    campuses,
    currentCampus,
    loading,
    params,
    setCampuses,
    setCurrentCampus,
    setLoading,
    fetch,
    refresh,
    deleteCampus,
  }
})
