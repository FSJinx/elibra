export interface AcaRepoPayload {
  title: string
  category: 'ut' | 'gt'
  abstract: string
  doi: string | null
  keywords: string[]

  status: 'pending' | 'approved' | 'rejected'
  statusRemark: string | null
  on_catalog: boolean

  language_id: number | null
  program_id: number | null
}

export interface AcaRepo extends AcaRepoPayload {
  id: number
  created_at: string
  updated_at: string

  language?: Partial<Language> | null
  program?: Partial<Program> | null
}

export interface AcaRepoCopy {
  id: any
  title: string
  category: string
  description: string
  doi: string
  keywords: string[]

  status: string
  statusRemark: string

  on_catalog: boolean

  library_id: any
  language_id: any
  program_id: any

  library: Library
  language: Language
  program: Program
}

export interface StoreAcaRepo extends Partial<AcaRepoCopy> {}