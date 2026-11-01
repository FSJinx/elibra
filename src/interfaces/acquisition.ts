export interface Acquisition {
  id: any
  reference?: string
  dealer: string
  acquisition_date: string
  remarks: string
  
  acquisition_mode_id: any
  receiver_id: any

  created_at?: string
  updated_at?: string

  acquisition_mode: AcquisitionMode
  acquisition_lines: AcquisitionLine
  receiver: User
}


export interface StoreAcquisition extends Partial<Acquisition> {}