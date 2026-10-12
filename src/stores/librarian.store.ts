import type { Librarian } from '@/interfaces/user'

interface LibrariansParams extends BaseParams {}

const defaultParams: Readonly<Partial<LibrariansParams>> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

export const librariansStore = defineStore('librarians', {
  state: () => ({
    data: null as Librarian[] | null,

    url: 'librarian',
    params: defaultParams as LibrariansParams,
  }),

  getters: {
    getFullName() {
      return (user: Librarian) => {
        if (!user) return ''

        const u = user
        return `${u.first_name} ${u.middle_initial ? u.middle_initial + '.' : ''} ${u.last_name ?? ''}`
      }
    },
  },

  actions: {
    async fetch() {
      try {
        const res = await get(this.url, this.params)
        return res
      } catch (e: any) {
        return e.response
      }
    },
  },
})
