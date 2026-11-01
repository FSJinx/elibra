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
  library?: Partial<Library>
  campus: Partial<Campus>
  department?: Partial<Department>
  // program?: Partial<Program>
  librarian: Partial<Librarian>
  patron: Partial<Patron>

  created_at: string
  updated_at: string
  deleted_at: string

  [key: string]: any
}
