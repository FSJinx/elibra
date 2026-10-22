export interface Author {
  id: any
  last_name: string
  first_name: string
  middle_name: string
  authorship_id?: number | null

  authorship: Authorship

  [key:string]: any
}
