export interface InventoryLineParams extends BaseParams {
    inventory_id: any
    verified: boolean | null
    status: string
}

export interface InventoryLine {
    id?: any
    inventory_id: any
    accession_id: any

    current_status: string
    current_section_id: any
    
    new_status: string
    new_section_id: any

    verifier_id: any
    verified_at: string

    // Relationships
    inventory: Inventory | null
    accession: Accession | null
    current_section: Section | null
    new_section: Section | null
    verifier: Librarian | null
}