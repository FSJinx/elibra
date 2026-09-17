interface Item {
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

export const useItemStore = defineStore('item', {
  state: () => ({
    fetchData: null as Item[] | null,
    searchData: null as Item[] | null,
    currentData: null as Item | null,
  }),

  getters: {
    data(): Item[] | null {
      return this.searchData ?? this.fetchData ?? null
    },
  },

  actions: {
    setFetchData(data: Item[]) {
      console.log(data)

      this.fetchData = data
    },

    setCurrentData(data: Item) {
      this.currentData = data
    },

    async fetch() {
      const res = await get('item/get')
      this.setFetchData(res.data?.data)
    },

    async show(id: any) {
      const res = await get(`item/get/${id}`)

      this.setCurrentData(res.data)
    },
  },
})
