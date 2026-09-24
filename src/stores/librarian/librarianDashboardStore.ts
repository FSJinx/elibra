const url = 'librarian/dashboard'

export const useLibrarianDashboardStore = defineStore('librarian_dashboard', {
  state: () => ({
    totalCollections: 0,
    totalAcademics: 0,
    totalSerials: 0,
    totalBooks: 0,
    totalPatrons: 0,
    totalLibrarians: 0,
    totalCampuses: 0,
    totalBranches: 0,
    loadingTotalCollections: false,
    loadingTotalAcademics: false,
    loadingTotalSerials: false,
    loadingTotalBooks: false,
    loadingTotalPatrons: false,
    loadingTotalLibrarians: false,
    loadingTotalCampuses: false,
    loadingTotalBranches: false,
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

    async fetchTotalAcademics() {
      this.loadingTotalAcademics = true

      try {
        const response = await get<{ data?: { total_academics?: number } }>(`${url}/total-academics`)
        this.totalAcademics = response.data?.total_academics ?? 0
      } finally {
        this.loadingTotalAcademics = false
      }
    },

    async fetchTotalSerials() {
      this.loadingTotalSerials = true

      try {
        const response = await get<{ data?: { total_serial?: number } }>(`${url}/total-serials`)
        this.totalSerials = response.data?.total_serial ?? 0
      } finally {
        this.loadingTotalSerials = false
      }
    },

    async fetchTotalBooks() {
      this.loadingTotalBooks = true

      try {
        const response = await get<{ data?: { total_books?: number } }>(`${url}/total-books`)
        this.totalBooks = response.data?.total_books ?? 0
      } finally {
        this.loadingTotalBooks = false
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

    async fetchTotalBranches() {
      this.loadingTotalBranches = true

      try {
        const response = await get<{ data?: { total_branches?: number } }>(`${url}/total-branches`)
        this.totalBranches = response.data?.total_branches ?? 0
      } finally {
        this.loadingTotalBranches = false
      }
    },

    async fetch() {
      await Promise.all([this.fetchTotalCollections(), this.fetchTotalAcademics(), this.fetchTotalSerials(), this.fetchTotalBooks(), this.fetchTotalPatrons(), this.fetchTotalLibrarians(), this.fetchTotalCampuses(), this.fetchTotalBranches()])
    },
  },
})
