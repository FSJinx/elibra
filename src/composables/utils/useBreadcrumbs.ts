const dynamicBreadcrumbs = ref<Record<string, string | null>>({})

export function useBreadcrumb() {
  function setBreadcrumb(routeName: string, label: string | null) {
    dynamicBreadcrumbs.value[routeName] = label
  }

  function getBreadcrumb(routeName: string, fallback: string) {
    return dynamicBreadcrumbs.value[routeName] || fallback
  }

  return {
    dynamicBreadcrumbs,
    setBreadcrumb,
    getBreadcrumb,
  }
}
