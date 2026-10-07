const url = 'librarian/dashboard'

export const useLibrarianDashboardStore = defineStore('librarian_dashboard', {
  state: () => ({
    totalCollections: 0,
    totalPatrons: 0,
    totalLibrarians: 0,
    totalCampuses: 0,
    loadingTotalCollections: false,
    loadingTotalPatrons: false,
    loadingTotalLibrarians: false,
    loadingTotalCampuses: false,
  }),

  getters: {},

  actions: {
    async fetchTotalCollections() {
      this.loadingTotalCollections = true

      try {
        const response = await get<{ data?: number }>(`${url}/total-collections`)
        this.totalCollections = response.data ?? 0
      } finally {
        this.loadingTotalCollections = false
      }
    },

    async fetchTotalPatrons() {
      this.loadingTotalPatrons = true

      try {
        const response = await get<{ data?: { total_patrons?: number } }>(`${url}/total-patrons`)
        this.totalPatrons = response.data?.total_patrons ?? 0
      } finally {
        this.loadingTotalPatrons = false
      }
    },

    async fetchTotalLibrarians() {
      this.loadingTotalLibrarians = true

      try {
        const response = await get<{ data?: { total_librarians?: number } }>(`${url}/total-librarians`)
        this.totalLibrarians = response.data?.total_librarians ?? 0
      } finally {
        this.loadingTotalLibrarians = false
      }
    },

    async fetchTotalCampuses() {
      this.loadingTotalCampuses = true

      try {
        const response = await get<{ data?: { total_campuses?: number } }>(`${url}/total-campuses`)
        this.totalCampuses = response.data?.total_campuses ?? 0
      } finally {
        this.loadingTotalCampuses = false
      }
    },

    async fetch() {
      await Promise.all([this.fetchTotalCollections(), this.fetchTotalPatrons(), this.fetchTotalLibrarians(), this.fetchTotalCampuses()])
    },
  },
})
