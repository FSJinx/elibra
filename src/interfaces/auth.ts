export interface ApiResponse {
  message?: string | null
  status?: string | null
  data?: string[] | null
}

export interface Login {
  username: string
  password: string
}
