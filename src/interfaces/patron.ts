export interface Patron {
  ebc_number: string
  external_organization: string
  date_joined: string
  account_expiry: string
  remarks: string

  user_id: any
  patron_type_id: any
  program_id: any

  user: User
  patron_type: Partial<PatronType>
  program: Partial<Program>
}
