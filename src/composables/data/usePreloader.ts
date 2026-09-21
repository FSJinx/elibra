export async function usePreloader() {
  const auth = useAuth()
  const campus = useCampusStore()
  const branch = useBranchStore()
  const item_type = useItemTypeStore()
  const category = useItemCategoriesStore()
  const authorship = authorshipStore()
  const language = useLanguagesStore()

  try {
    // ======== PUBLIC PRELOAD ===========
    campus.fetch()
    branch.fetch()
    item_type.fetch()
    category.fetch()
    authorship.fetch()
    language.fetch()
  } catch (err) {
    throw err
  }
}
