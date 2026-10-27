export const attendanceStore = defineStore('attendance', {
    state: () => ({
        data: null as Attendance[] | null,

        url: 'attendance'
    }),

    actions: {
        async create(params: {}) {
            const res = await post(this.url, params)
            return res
        }
    }
})