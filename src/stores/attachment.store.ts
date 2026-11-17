const pop = usePopup()
export const attachmentStore = defineStore('attachments', {
  state: () => ({
    url: 'attachment',
  }),

  actions: {
    async download(payload: Attachment) {
      pop.load()
      const res = await get(`${this.url}/${payload.id}/download`, null, { responseType: 'blob' })
      pop.unload()
      const url = URL.createObjectURL(res)
      const link = Object.assign(document.createElement('a'), {
        href: url,
        download: payload.original_name,
      })
      link.click()
      link.remove()
      URL.revokeObjectURL(url)
    },
  },
})
