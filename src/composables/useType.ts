export type Variants = 'primary' | 'default' | 'success' | 'danger' | 'restore' | 'warning' | 'info' | 'text'
export type Sizes = 'xs' | 'sm' | 'md' | 'lg' | 'xl'

export const sizes: Record<Sizes, string> = {
  xs: 'text-xs',
  sm: 'text-sm',
  md: 'text-base',
  lg: 'text-xl',
  xl: 'text-3xl',
}
