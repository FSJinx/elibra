export function usePreloader() {
  const preloaded = ref<boolean>(false)
  async function preload() {
    const auth = useAuth()
    const campus = useCampusStore()
    const library = libraryStore()
    const item_type = useItemTypeStore()
    const category = useItemCategoriesStore()
    const authorship = authorshipStore()
    const language = useLanguagesStore()

    const { fetchPatronTypes, fetchSections } = optionStore()

    if (!preloaded.value) {
      try {
        // ======== PUBLIC PRELOAD ===========
        await Promise.all([
          // ======== ORGANIZATION WIDE PRELOAD ===========
          campus.fetch(),
          library.fetch(),
          fetchSections(),

          // ======== USER WIDE PRELOAD ===========
          fetchPatronTypes(),

          // ======== ITEM WIDE PRELOAD ===========
          item_type.fetch(),
          category.fetch(),
          authorship.fetch(),
          language.fetch(),
        ])
      } catch (err) {
        throw err
      } finally {
        preloaded.value = true
      }
    }
  }

  return {
    preloaded,
    preload,
  }
}
