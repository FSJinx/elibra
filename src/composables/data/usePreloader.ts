export async function usePreloader() {
  const auth = useAuth()
  const campus = useCampus()
  const branch = useBranch()

  try {
    // ======== PUBLIC PRELOAD ===========
    campus.getCampuses()
    branch.getBranches()
  } catch (err) {
    throw err
  }
}
