export interface AcaRepoPayload {
  title: string
  category: 'ut' | 'gt'
  abstract: string
  doi: string | null
  library_id: number | null
  keywords: string[]

  status: 'pending' | 'approved' | 'rejected'
  statusRemark: string | null
  on_catalog: boolean

  language_id: number | null
  program_id: number | null
}

export interface AcaRepo extends AcaRepoPayload {
  id: number
  electronic_file: string
  submitted_by: number
  created_at: string
  updated_at: string

  language?: Partial<Language> | null
  program?: Partial<Program> | null
  submitter?: Partial<User> | null
}
