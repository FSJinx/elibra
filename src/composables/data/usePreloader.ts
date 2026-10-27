export function usePreloader() {
  const preloaded = ref<boolean>(false)
  async function preload() {
    const options = optionStore()

    if (!preloaded.value) {
      try {
        await options.preload()
        // ======== PUBLIC PRELOAD ===========
        await Promise.all([
          // ======== ORGANIZATION WIDE PRELOAD ===========
          // campus.fetch(),
          // library.fetch(),
          // options.fetchCampuses(),
          // options.fetchLibraries(),
          // options.fetchSections(),
          // options.fetch('campuses'),
          // options.fetch('departments'),
          // options.fetch('libraries'),
          // options.fetch('sections'),
          // // ======== STANDARD PRELOAD ===========
          // options.fetchAuthorships(),
          // options.fetchCategories(),
          // options.fetchItemTypes(),
          // options.fetchPatronTypes(),
          // // ======== AUTHORITY CONTROL PRELOAD ===========
          // options.fetchLanguages(),
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
