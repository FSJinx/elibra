export interface Attachment {
    id: any
    disk: string
    collection: string
    path: string
    original_name: string
    mime_type: string
    size: number
    uploader_id: any

    uploader: User | null
}