import type { Form } from '@/app/librarian/collections/catalog/forms/form'

interface Draft {
  id: any
  key: string
  data: any
  created_at: string
}

export const draftStore = defineStore('drafts', {
  state: () => ({
    data: [] as Draft[],
  }),

  actions: {
    add(key: string, data: any) {
      const date = new Date()
      this.data.push({
        id: crypto.randomUUID(),
        key: key,
        data: data,
        created_at: date.toISOString(),
      })
    },

    saveItem(data: Partial<Form>) {
      this.add('item', data)
    },
  },

  persist: {
    pick: ['data'],
  },
})
