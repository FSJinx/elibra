export interface InventoryParams {
  sort: string
  page: number
  per_page: number
  order: 'asc' | 'desc'
}

export interface Inventory {
  id: any
  inventory_code: string
  librarian_id: any
  section_id: any
  closed_at: string
  status: string

  section: Section | null
  librarian: Librarian | null

  created_at: string
  updated_at: string
}

export interface StoreInventory extends Partial<Inventory> {
  shelved: boolean | null
}

export interface UpdateInventory extends Partial<Inventory> {}
