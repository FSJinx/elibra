export interface Accession {
  id: any
  accession_number: string
  status: string
  remarks: string
  item_id: string
  section_id: string
  acquisition_line_id: string

  acquisition: Partial<Acquisition>
  section: Partial<Section>
  item: Partial<Item>

  [key: string]: any
}
