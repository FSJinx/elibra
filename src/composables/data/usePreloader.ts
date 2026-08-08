export async function usePreloader() {
  const auth = useAuth()
  const campus = useCampus()

  try {
    // ======== PUBLIC PRELOAD ===========
    campus.getCampuses()
  } catch (err) {
    throw err
  }
}
