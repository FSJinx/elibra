export const attendanceStore = defineStore('attendance', {
  state: () => ({
    data: null as Attendance[] | null,

    url: 'attendance',
  }),

  actions: {
    async fetch(params?: Partial<AttendanceParams>) {
      const res = await get(this.url, params)
      this.data = res.data.data
      return res
    },

    async create(payload: {}) {
      const res = await post(this.url, payload)
      return res
    },
  },
})
