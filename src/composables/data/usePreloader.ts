export function usePreloader() {
  const preloaded = ref<boolean>(false)
  async function preload() {
    const auth = useAuth()
    const campus = useCampusStore()
    const branch = useBranchStore()
    const item_type = useItemTypeStore()
    const category = useItemCategoriesStore()
    const authorship = authorshipStore()
    const language = useLanguagesStore()

    if (!preloaded.value) {
      try {
        // ======== PUBLIC PRELOAD ===========
        Promise.all([campus.fetch(), branch.fetch(), item_type.fetch(), category.fetch(), authorship.fetch(), language.fetch()])
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
