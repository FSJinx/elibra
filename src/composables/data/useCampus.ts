export function useCampus() {
  // ============= STATES ===============
  const error = useError()
  const store = campusStore()
  const { campuses, currentCampus, loading } = storeToRefs(store)

  // ============= ACTIONS ===============
  async function fetchCampus() {
    if (store.campuses && store.campuses?.length <= 0) return store.campuses

    store.setLoading(true)
    try {
      const res = await api.get('/campus/get')

      store.setCampuses(res.data.data)
      return res?.data.data
    } catch (err) {
      error.open({ message: 'Error' })
    } finally {
      store.setLoading(false)
    }
  }

  return {
    // ============= ACTIONS ===============
    fetchCampus,
  }
}
