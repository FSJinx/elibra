export interface Opac {
  [key: string]: any
}

const url = 'opac'

export const useOpacStore = defineStore('opac', {
  state: () => ({
    currentData: null as Opac | null,
  }),

  actions: {
    async show(id: any) {
      try {
        const res = await get(`${url}/item/${id}`)

        this.currentData = res?.data
      } catch (e: any) {
        throw e
      }
    },
  },
})
