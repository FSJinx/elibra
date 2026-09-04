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

export const campusStore = defineStore('campus', () => {
  // ============= STATES ===============
  const campuses = ref<Campus[] | null>(null)
  const currentCampus = ref<Campus | null>(null)
  const loading = ref<boolean>(false)

  // ============= SETTERS ===============
  function setCampuses(data: Campus[] | null) {
    campuses.value = data
  }

  function setCurrentCampus(data: Campus | null) {
    currentCampus.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  return {
    // ============= STATES ===============
    campuses,
    currentCampus,
    loading,

    // ============= SETTERS ===============
    setCampuses,
    setCurrentCampus,
    setLoading,

  }
})
