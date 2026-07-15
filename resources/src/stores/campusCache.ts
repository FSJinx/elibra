import { defineStore } from 'pinia'
import api from '../plugins/axios'

export interface CampusInfo {
  id: number | null
  name: string | null
  code: string | null
  address: string | null
  heading: string | null
  created_at: string | null
  updated_at: string | null
}

interface Campus {
  campuses: CampusInfo[]
  currentCampus: CampusInfo | null
  loading: boolean
}

export const useCampus = defineStore('campus', {
  state: (): Campus => ({
    campuses: [],
    currentCampus: null,
    loading: false,
  }),

  actions: {
    setCampuses(data: CampusInfo[]) {
      this.campuses = data
    },

    setCampus(data: CampusInfo) {
      this.currentCampus = data
    },

    async fetchCampuses(query: string | null = null) {
      if (this.campuses.length > 0) return this.campuses

      this.loading = true
      await api
        .get('campus/get', {
          params: {
            query,
          },
        })
        .then((res) => {
          this.setCampuses(res.data.data)
        })
        .finally(() => {
          this.loading = false
        })

      return this.campuses
    },

    async refresh() {
      this.campuses = []
      await this.fetchCampuses()
    },

    view(id: any) {
      const campus = this.campuses.find((c) => c.id === id)

      if (campus) {
        this.currentCampus = campus
      } else {
        this.currentCampus = null
      }

      console.log(this.currentCampus)
      return this.currentCampus
    },
  },
})
