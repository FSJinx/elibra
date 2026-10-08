export interface AcquisitionLine {
  id?: any
  quantity: number | null
  unit_price: number | null

  item_id: any
  acquisition_id: any
  [key: string]: any
}
