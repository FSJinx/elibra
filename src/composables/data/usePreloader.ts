export async function usePreloader() {
  const auth = useAuth()
  const campus = useCampus()
  const branch = useBranch()
  const item_type = useItemTypes()
  const category = useItemCategories()
  const authorship = useAuthorship()
  const language = useLanguages()

  try {
    // ======== PUBLIC PRELOAD ===========
    campus.getCampuses()
    branch.getBranches()
    item_type.getItemTypes()
    category.getItemCategories()
    authorship.getAuthorships()
    language.getLanguages()
  } catch (err) {
    throw err
  }
}
