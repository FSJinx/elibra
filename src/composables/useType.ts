export type Variants = 'primary' | 'secondary' | 'success' | 'danger' | 'restore' | 'warning' | 'info' | 'text'
export type Sizes = 'xsmall' | 'small' | 'default' | 'large' | 'xlarge'
export type Radius = 'rounded' | 'cube' | 'pill'

export const radi: Record<Radius, string> = {
  rounded: 'rounded',
  cube: 'rounded-lg',
  pill: 'rounded-full',
}

export const sizes: Record<Sizes, string> = {
  xsmall: 'text-xs',
  small: 'text-sm',
  default: 'text-base',
  large: 'text-lg',
  xlarge: 'text-xl',
}
