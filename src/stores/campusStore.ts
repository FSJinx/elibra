export interface CampusInfo {
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
  const campuses = ref<CampusInfo[] | null>(null)
  const currentCampus = ref<CampusInfo | null>(null)
  const loading = ref<boolean>(false)

  
  // ============= SETTERS ===============
  function setCampuses(data: CampusInfo[] | null) {
    campuses.value = data
  }

  function setCurrentCampus(data: CampusInfo | null) {
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
