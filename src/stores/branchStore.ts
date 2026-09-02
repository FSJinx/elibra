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

export const branchStore = defineStore('branches', () => {
  const branches = ref<Branch[]>([])
  const currentBranch = ref<Branch | null>(null)
  const loading = ref<boolean>(false)

  function setBranches(data: Branch[]) {
    branches.value = data
  }

  function setCurrentBranch(data: Branch | null) {
    currentBranch.value = data
  }

  function setLoading(status: boolean) {
    loading.value = status
  }

  return {
    branches,
    currentBranch,
    loading,

    setBranches,
    setCurrentBranch,
    setLoading,
  }
})