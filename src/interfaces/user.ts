export interface UserParams extends Partial<BaseParams> {
  campus_id: any
  role: string
}

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
  registration: 'approved' | 'pending' | 'rejected' | null
  login_attempts: string
  profile_picture_id: any
  campus_id: any
  remember_token: any

  // RELATIONSHIPS
  library: Library | null
  campus: Campus | null
  department: Department | null
  program: Program | null
  librarian: Librarian | null
  patron: Patron | null

  created_at: string
  updated_at: string
  deleted_at: string

  // Appended Values

  name: {
    full: string
    formal: string
  }

  role_text: string

  [key: string]: any
}
