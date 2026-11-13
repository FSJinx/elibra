export interface HoldingParams extends BaseParams {
  patron_id: any
}

export interface Holding {
  id: any
  reserver_id: any
  accession_id: any
  processer_id: any

  pickup_date: string
  expected_pickup_date: string

  status: string
  status_remarks: string

  reserver: Partial<Patron>
  accession: Partial<Accession>
  processor: Partial<Librarian>

  [key: string]: any
}

export interface StoreHolding extends Partial<Holding> {}
export interface UpdateHolding extends Partial<Holding> {}
