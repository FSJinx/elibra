export type Variants = 'primary' | 'default' | 'success' | 'danger' | 'restore' | 'warning' | 'info' | 'text'
export type Sizes = 'xsmall' | 'small' | 'default' | 'large' | 'xlarge'

export const sizes: Record<Sizes, string> = {
  xsmall: 'text-xs',
  small: 'text-sm',
  default: 'text-base',
  large: 'text-xl',
  xlarge: 'text-3xl',
}
