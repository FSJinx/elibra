export interface ItemParams extends BaseParams {}

const defaultParams: Readonly<ItemParams> = {
  query: '',
  order: 'asc',
  sort: 'title',
  page: '',
  per_page: '25',
}

const url = '/item'

export const useItemStore = defineStore('item', {
  state: () => ({
    data: null as Item[] | null,
    currentData: null as Item | null,
    params: defaultParams as ItemParams,

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

    async fetch(params: ItemParams = defaultParams) {
      const res = await get(url, params)
      this.setData(res.data?.data)
      return res
    },

    async show(id: any) {
      const res = await get(`${url}/${id}`)

      this.setCurrentData(res.data)
    },
  },
})
