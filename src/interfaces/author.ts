export interface AuthorParams extends Partial<BaseParams> {}

export interface Author {
  id: any
  last_name: string
  first_name: string
  middle_name: string
  authorship_id?: number | null

  authorship: Authorship

  name?: {
    full: string
    formal: string
    author: string
    author_reversed: string
  }

  [key: string]: any
}

export interface StoreAuthor extends Partial<Author> {}
