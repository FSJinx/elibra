export interface AccessionParams extends BaseParams {
  status: string
  item_id: any
}

export interface Accession {
  id: any
  accession_number: string
  status: string
  remarks: string
  
  item_id: string
  section_id: string
  acquisition_line_id: any

  acquisition: Partial<Acquisition>
  section: Partial<Section>
  item: Partial<Item>

  [key: string]: any
}

export interface StoreAccession extends Partial<Accession> {
  quantity: number
}
export interface UpdateAccession extends Partial<Accession> {}