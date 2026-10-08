export interface ItemParams extends BaseParams {}

export const itemDefaultParams = (): Partial<ItemParams> => ({
  query: '',
  order: 'asc',
  sort: 'title',
  page: '',
  per_page: '25',
})

const url = '/item'

export const useItemStore = defineStore('item', {
  state: () => ({
    data: null as Item[] | null,
    currentData: null as Item | null,
    params: itemDefaultParams() as ItemParams,

    item_type: useItemTypeStore(),
    categories: useItemCategoriesStore(),
    languages: useLanguagesStore(),
    libraries: libraryStore(),
  }),

  getters: {
    getItemType() {
      return (id: number) => this.categories.data?.find((i) => i.id === id) ?? null
    },
    getCategory() {
      return (id: number) => this.categories.data?.find((i) => i.id === id) ?? null
    },
    getLanguage() {
      return (id: number) => this.languages.data?.find((i) => i.id === id) ?? null
    },
    getBranches() {
      return (id: number) => this.libraries.data?.find((i) => i.id === id) ?? null
    },
  },

  actions: {
    setData(data: Item[]) {
      this.data = data
    },

    setCurrentData(data: Item) {
      this.currentData = data
    },

    async create(params: Partial<Item>) {
      const res = await post(url, params)
      this.data?.reverse().push(res.data)
      return res
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
