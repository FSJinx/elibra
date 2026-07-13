export type Variants = 'default' | 'default-hover' | 'solid' | 'solid-hover' | 'outline' | 'outline-hover' | 'outline-solid' | 'outline-soft' | 'outline-soft-hover' | 'soft' | 'soft-hover' | 'text' | 'text-hover'
export type Colors = 'success' | 'danger' | 'info' | 'warning' | 'text'

export const variants: Record<Variants, Record<Colors, string>> = {
  default: {
    success: 'border-transparent text-green-600 dark:text-green-500',
    danger: 'border-transparent text-red-600 dark:text-red-500',
    info: 'border-transparent text-blue-600 dark:text-blue-500',
    warning: 'border-transparent text-yellow-500 dark:text-yellow-500',
    text: 'border-transparent text-slate-700 dark:text-slate-300',
  },
  'default-hover': {
    success: 'border-transparent text-green-600 hover:bg-green-50 dark:hover:bg-green-950/30',
    danger: 'border-transparent text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30',
    info: 'border-transparent text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30',
    warning: 'border-transparent text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-950/30',
    text: 'border-transparent text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800',
  },
  solid: {
    success: 'border-transparent bg-green-500 text-white',
    danger: 'border-transparent bg-red-500 text-white',
    info: 'border-transparent bg-blue-500 text-white',
    warning: 'border-transparent bg-yellow-400 text-slate-900', // Slate text para sa magandang contrast sa yellow
    text: 'border-transparent bg-slate-500 text-white dark:bg-slate-200 dark:text-slate-900',
  },
  'solid-hover': {
    success: 'border-transparent bg-green-500 text-white hover:bg-green-600',
    danger: 'border-transparent bg-red-500 text-white hover:bg-red-600',
    info: 'border-transparent bg-blue-500 text-white hover:bg-blue-600',
    warning: 'border-transparent bg-yellow-500 text-slate-900 hover:bg-yellow-600',
    text: 'border-transparent bg-slate-500 text-white hover:bg-slate-600 dark:bg-slate-200 dark:text-slate-900 dark:hover:bg-slate-100',
  },
  outline: {
    success: 'border-green-600 text-green-600 bg-transparent',
    danger: 'border-red-600 text-red-600 bg-transparent',
    info: 'border-blue-600 text-blue-600 bg-transparent',
    warning: 'border-yellow-500 text-yellow-500 bg-transparent',
    text: 'border-slate-300 text-slate-700 bg-transparent dark:border-slate-700 dark:text-slate-300',
  },
  'outline-hover': {
    success: ' border-green-600 text-green-600 hover:bg-green-50 dark:hover:bg-green-950/30',
    danger: ' border-red-600 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30',
    info: ' border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30',
    warning: ' border-yellow-500 text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-950/30',
    text: ' border-slate-500 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800/50',
  },
  'outline-solid': {
    success: ' border-green-500 text-green-600 hover:bg-green-500 hover:text-white dark:hover:bg-green-500',
    danger: ' border-red-500 text-red-600 hover:bg-red-500 hover:text-white dark:hover:bg-red-500',
    info: ' border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500',
    warning: ' border-yellow-400 text-yellow-500 hover:bg-yellow-400 hover:text-slate-900 dark:hover:bg-yellow-500',
    text: ' border-slate-500 text-slate-700 hover:bg-slate-500 hover:text-white dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-500',
  },
  'outline-soft': {
    success: 'border-green-200 bg-green-50 text-green-700 dark:border-green-900/50 dark:bg-green-950/20 dark:text-green-400',
    danger: 'border-red-200 bg-red-50 text-red-700 dark:border-red-900/50 dark:bg-red-950/20 dark:text-red-400',
    info: 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-900/50 dark:bg-blue-950/20 dark:text-blue-400',
    warning: 'border-yellow-200 bg-yellow-50 text-yellow-700 dark:border-yellow-900/50 dark:bg-yellow-950/20 dark:text-yellow-400',
    text: 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-300',
  },
  'outline-soft-hover': {
    success: ' border-green-200 bg-green-50 text-green-700 hover:bg-green-100 dark:border-green-900/50 dark:bg-green-950/20 dark:text-green-400 dark:hover:bg-green-950/40',
    danger: ' border-red-200 bg-red-50 text-red-700 hover:bg-red-100 dark:border-red-900/50 dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/40',
    info: ' border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-950/20 dark:text-blue-400 dark:hover:bg-blue-950/40',
    warning: ' border-yellow-200 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 dark:border-yellow-900/50 dark:bg-yellow-950/20 dark:text-yellow-400 dark:hover:bg-yellow-950/40',
    text: ' border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-300 dark:hover:bg-slate-800',
  },
  soft: {
    success: 'border-transparent bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400',
    danger: 'border-transparent bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400',
    info: 'border-transparent bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400',
    warning: 'border-transparent bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400',
    text: 'border-transparent bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
  },
  'soft-hover': {
    success: 'border-transparent bg-green-50 text-green-700 hover:bg-green-100 dark:bg-green-950/30 dark:text-green-400 dark:hover:bg-green-950/50',
    danger: 'border-transparent bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-950/30 dark:text-red-400 dark:hover:bg-red-950/50',
    info: 'border-transparent bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-950/30 dark:text-blue-400 dark:hover:bg-blue-950/50',
    warning: 'border-transparent bg-yellow-50 text-yellow-700 hover:bg-yellow-100 dark:bg-yellow-950/30 dark:text-yellow-400 dark:hover:bg-yellow-950/50',
    text: 'border-transparent bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700',
  },
  text: {
    success: 'border-slate-300 text-green-600 bg-slate-50',
    danger: 'border-slate-300 text-red-600 bg-slate-50',
    info: 'border-slate-300 text-blue-600 bg-slate-50',
    warning: 'border-slate-300 text-yellow-500 bg-slate-50',
    text: 'border-slate-300 text-slate-600 bg-slate-50 dark:text-slate-400',
  },
  'text-hover': {
    success: 'bg-slate-50 border-slate-300 text-green-600 hover:shadow-[0_0_0_.15rem] hover:shadow-green-200/80 dark:hover:bg-green-950/30',
    danger: 'bg-slate-50 border-slate-300 text-red-600 hover:shadow-[0_0_0_.15rem] hover:shadow-red-200/80 dark:hover:bg-red-950/30',
    info: 'bg-slate-50 border-slate-300 text-blue-600 hover:shadow-[0_0_0_.15rem] hover:shadow-blue-200/80 dark:hover:bg-blue-950/30',
    warning: 'bg-slate-50 border-slate-300 text-yellow-500 hover:shadow-[0_0_0_.15rem] hover:shadow-yellow-200/80 dark:hover:bg-yellow-950/30',
    text: 'bg-slate-50 border-slate-300 text-slate-800 hover:shadow-[0_0_0_.15rem] hover:shadow-secondary/40 dark:text-slate-400 dark:hover:bg-slate-800',
  },
}
