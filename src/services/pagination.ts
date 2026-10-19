export interface Pagination {
  current_page: any
  data: any
  first_page_url: string

  from: any
  last_page: any
  last_page_url: string
  links: [
    {
      url: null
      label: '&laquo; Previous'
      page: null
      active: false
    },
  ]
  next_page_url: string
  path: string
  per_page: any
  prev_page_url: string
  to: any
  total: any
}
