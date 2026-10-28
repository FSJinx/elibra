export interface Holding {
  id: any
  reserved_by: any
  accession_id: any
  processed_by: any

  pickup_date: string
  picked_up_at: string

  reserver: Partial<Patron>
  accesion: Partial<Accession>
  proccessor: Partial<Librarian>

  [key: string]: any
}
