interface Language {
  id: number
  code: string
  name: string
  created_at: string
  updated_at: string
}

export const languagesStore = defineStore('languages', () => {
  const languages = ref<Language[] | null>(null)
  const currentLanguage = ref<Language | null>(null)
  const loading = ref(false)

  function setLanguages(data: Language[] | null) {
    languages.value = data
  }

  function setCurrentLanguage(data: Language | null) {
    currentLanguage.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  return {
    languages,
    currentLanguage,
    loading,
    setLanguages,
    setCurrentLanguage,
    setLoading,
  }
})
