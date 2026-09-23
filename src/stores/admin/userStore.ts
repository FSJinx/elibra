export interface User {
  id: any
  uuid: string
  last_name: string
  first_name: string
  middle_initial: string
  sex: string
  birthdate: string
  contact_number: string
  email: string
  email_verified_at: any
  username: string
  password: string
  role: string
  status: string
  login_attempts: string
  profile_picture_id: any
  campus_id: any
  remember_token: any

  created_at: string
  updated_at: string
  deleted_at: string
}

const url = 'users'

export const useAdminUserStore = defineStore('admin_users', {
  state: () => ({
    data: null as User[] | null,
    currentData: null as User | null,
    loading: false as boolean,
    pop: usePopup(),
  }),

  getters: {},

  actions: {
    setData(data: User[]) {
      this.data = data
    },

    async fetch(forced = false) {
      if (this.data && !forced) return

      try {
        this.loading = true
        const res = await get(url)
        this.setData(res.data)
        return res
      } catch (e: any) {
        throw e
      } finally {
        this.loading = false
      }
    },
  },
})
