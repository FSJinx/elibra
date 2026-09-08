interface Branch {
  id: number
  name: string
  contact_info: string
  email: string
  email_verified_at: string
  opening_hour: string
  closing_hour: string
  logo_id: number
  branch_head_id: number
  campus_id: number
  created_at: string
  updated_at: string
}

interface BranchParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

const defaultParams: Readonly<BranchParams> = {
  sort: '',
  page: 1,
  per_page: 10,
  order: 'asc',
}

export const useBranchStore = defineStore('branches', () => {
  const branches = ref<Branch[]>([])
  const currentBranch = ref<Branch | null>(null)
  const loading = ref<boolean>(false)
  const params = reactive<BranchParams>({ ...defaultParams })

  function setBranches(data: Branch[]) {
    branches.value = data
  }

  function setCurrentBranch(data: Branch | null) {
    currentBranch.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  async function fetch(forced = false) {
    if (!forced && branches.value.length) return branches.value

    setLoading(true)

    try {
      const response = await get('branch', { ...params })
      const data = response.data.data.data

      setBranches(data)
      return data
    } catch (error) {
      console.error('Error fetching branches:', error)
      return []
    } finally {
      setLoading(false)
    }
  }

  async function refresh() {
    Object.assign(params, defaultParams)
    return fetch(true)
  }

  return {
    branches,
    currentBranch,
    loading,
    params,

    setBranches,
    setCurrentBranch,
    setLoading,
    fetch,
    refresh,
  }
})
