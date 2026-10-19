export interface Acquisition {
  id: any
  reference?: string
  dealer: string
  acquisition_mode: string
  acquisition_date: string
  remarks: string

  receiver_id: any

  created_at?: string
  updated_at?: string

  acquisition_lines: AcquisitionLine
  receiver: User
}
