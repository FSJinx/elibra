import { onBeforeUnmount, onMounted, type Ref } from 'vue'

export function useClickOutside(el: Ref<HTMLElement | null>, callback: () => void) {
  const handler = (e: MouseEvent) => {
    const target = e.target as Node
    if (el?.value && !el.value.contains(target)) {
      callback()
    }
  }

  onMounted(() => {
    document.addEventListener('click', handler)
  })

  onBeforeUnmount(() => {
    document.removeEventListener('click', handler)
  })
}
