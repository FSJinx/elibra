export interface AcaRepoParams extends BaseParams {
  submitted_by: any
}
export interface AcaRepo {
  id: any
  title: string
  category: string
  description: string
  doi: string
  electronic_file: File | string | null
  keywords: string[]

  status: string
  statusRemark: string
  on_catalog: boolean

  library_id: any
  submitted_by: any
  language_id: any
  program_id: any

  library: Library
  submitter: Patron
  language: Language
  program: Program

  created_at: string
  updated_at: string
}

export interface StoreAcaRepo extends Partial<AcaRepo> {}
export interface UpdateAcaRepo extends Partial<AcaRepo> {}
