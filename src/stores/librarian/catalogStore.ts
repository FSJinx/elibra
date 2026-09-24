export interface Catalog {
  id: any
  title: string
  subtitle?: string
  description: string
  call_number: string
  publication_year: string
  keywords: string
  electronic_file: string

  item_type_id: any
  item_type_category_id: any
  branch_id: any
  language_id: any

  [key: string]: any
}

export interface CatalogParams extends BaseParams {}

export const catalogDefaultParams = (): Partial<CatalogParams> => ({
  query: '',
  order: 'asc',
  sort: 'title',
  page: '',
  per_page: '25',
})

const url = '/librarian/collections/catalog'

export const useCatalogStore = defineStore('catalog', {
  state: () => ({
    data: null as Catalog[] | null,
    currentData: null as Catalog | null,
    params: catalogDefaultParams() as CatalogParams,

    item_type: useItemTypeStore(),
    categories: useItemCategoriesStore(),
    languages: useLanguagesStore(),
    branches: useBranchStore(),
  }),

  getters: {
    getItemType() {
      return (id: number) => this.categories.categories?.find((i) => i.id === id) ?? null
    },
    getCategory() {
      return (id: number) => this.categories.categories?.find((i) => i.id === id) ?? null
    },
    getLanguage() {
      return (id: number) => this.languages.languages?.find((i) => i.id === id) ?? null
    },
    getBranches() {
      return (id: number) => this.branches.data?.find((i) => i.id === id) ?? null
    },
  },

  actions: {
    setData(data: Catalog[]) {
      this.data = data
    },

    setCurrentData(data: Catalog) {
      this.currentData = data
    },

    async fetch(forced = false) {
      if (!forced && this.data) return this.data

      const res = await get(url, this.params)
      this.setData(res.data?.data)
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)

      this.setCurrentData(res.data)
    },
  },
})
