// stores/breadcrumbStore.ts
export const useBreadcrumbStore = defineStore('breadcrumb', () => {
  const overrides = ref<Record<string, string>>({})

  const set = (key: string, label: string) => {
    overrides.value[key] = label
  }

  return { overrides, set }
})