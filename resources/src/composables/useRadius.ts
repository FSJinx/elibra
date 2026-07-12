export type Radius = 'rounded' | 'cube' | 'pill'

export const getRadius = (radius: Radius) => {
  const radi: Record<Radius, string> = {
    rounded: 'rounded',
    cube: 'rounded-lg',
    pill: 'rounded-full',
  }

  return radi[radius]
}
