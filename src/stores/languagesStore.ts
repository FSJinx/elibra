interface Language {
  id: number
  code: string
  name: string
  created_at: string
  updated_at: string
}

interface LanguageParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<LanguageParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

export const useLanguagesStore = defineStore('languages', () => {
  const languages = ref<Language[] | null>(null)
  const currentLanguage = ref<Language | null>(null)
  const loading = ref(false)
  const params = reactive<LanguageParams>({ ...defaultParams })

  function setLanguages(data: Language[] | null) {
    languages.value = data
  }

  function setCurrentLanguage(data: Language | null) {
    currentLanguage.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  async function fetch(forced = false) {
    if (!forced && languages.value?.length) return languages.value

    setLoading(true)

    try {
      const response = await api.get('languages', { params: { ...params } })
      const data = response.data?.data ?? []

      setLanguages(data)
      return data
    } catch (error) {
      console.error('Error fetching languages:', error)
      return []
    } finally {
      setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return fetch(true)
  }

  watchDebounced(
    () => ({ ...params }),
    () => fetch(true),
  )

  return {
    languages,
    currentLanguage,
    loading,
    params,
    setLanguages,
    setCurrentLanguage,
    setLoading,
    fetch,
    refresh,
  }
})
