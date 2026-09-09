export interface Acquisition {
  id: any
  acquisition_id?: string
  dealer: string
  acquisition_mode: string
  acquisition_date: string
  remarks: string
}

const baseUrl = 'acquisition/'

export const useAcquisitionStore = defineStore('acquisition', () => {
  const acquisitions = ref<Acquisition[] | null>()
  const loading = ref<boolean>(false)

  const pop = usePopup()

  async function fetch() {}

  async function store() {
    const confirm = await pop.confirm({ text: 'Are you sure you want to add this acquisition transaction?' })

    if (confirm.isConfirmed) {
      try {
        // const res = await api.get()
      } catch (e: any) {}
    }
  }

  return {
    acquisitions,
    loading,

    fetch,
    store,
  }
})
