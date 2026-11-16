export interface ReportParams extends BaseParams {
  type: string
}

export interface Report {
  id: any
  reference: string
  type: string
  format: string
  filters: Record<string, any>
  data: object

  Attachment_id: number
  generator_id: number
  library_id: number

  file: Attachment | null
  generator: Librarian | null
}

export interface StoreReport {
  format: string
  filters: Record<string, any>
}
