interface SearchHistory {
  title: string
  datetime: string
}

export const opacSearchStore = defineStore(
  'opacSearch',
  () => {
    const history = ref<SearchHistory[]>([])
    const pop = usePopup()

    const addHistory = (title: string) => {
      const entry: SearchHistory = {
        title,
        datetime: new Date().toISOString(),
      }

      history.value = history.value.filter((h) => h.title !== title)

      history.value.push(entry)
    }

    const deleteHistory = async (item: SearchHistory) => {
      const res = await pop.confirm({ text: `Are you sure you want to delete "${item.title}" from your recent searches?` })

      if (res.isConfirmed) {
        history.value = history.value.filter((h) => h !== item)

        pop.info('Deleted successfully')
      }
    }

    async function clearHistory() {
      const res = await pop.confirm({ text: `Are you sure you want to delete all your recent searches?` })

      if (res.isConfirmed) {
        history.value = []

        pop.info('History cleared successfully')
      }
    }

    return {
      history,

      addHistory,
      deleteHistory,
      clearHistory,
    }
  },
  { persist: { pick: ['history'] } },
)
