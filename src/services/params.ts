export interface BaseParams {
  query: string
  sort: string
  order: 'asc' | 'desc'
  page: any
  per_page: '25' | '50' | '75' | '100'
}
