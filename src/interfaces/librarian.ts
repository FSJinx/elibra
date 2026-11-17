export interface LibrarianParams extends Partial<BaseParams> {
  library_id: any
}

export interface Librarian {
  id: any

  user_id: any
  library_id: any

  user: User
  library: Library
}

export interface StoreLibrarian extends Partial<Librarian> {}
export interface UpdateLibrarian extends Partial<Librarian> {}
