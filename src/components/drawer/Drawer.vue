<template>
  <Transition name="fade">
    <Teleport to="body">
      <div v-if="isOpen" class="drawer fixed inset-0 flex bg-backdrop h-dvh overflow-hidden" :class="drawerParentClass">
        <div class="drawer-panel relative flex flex-col bg-background h-full w-full max-w-120 shadow-2xl">
          <button type="button" class="absolute top-3 right-3 z-10 p-2 text-lg text-foreground/25 hover:text-foreground cursor-pointer transition-all duration-200" aria-label="Close drawer" @click="close">
            <Icon icon="x-lg" style="-webkit-text-stroke: 1px" />
          </button>
          <slot />
        </div>
      </div>
    </Teleport>
  </Transition>
</template>

<script setup lang="ts">
interface Props {
  align?: 'left' | 'right'
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  align: 'right',
  loading: false,
})

const isOpen = ref(false)
const drawerParentClass = computed(() => props.align)

function open() {
  isOpen.value = true
}

function close() {
  isOpen.value = false
}

function handleKeydown(event: KeyboardEvent) {
  if (event.key === 'Escape' && isOpen.value) {
    close()
  }
}

watch(isOpen, (opened) => {
  if (opened) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.removeProperty('overflow')
  }
})

defineExpose({
  open,
  close,
})

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeydown)
  document.body.style.removeProperty('overflow')
})

provide('drawer', props)
</script>

<style scoped>
.drawer.left {
  justify-content: start;
}

.drawer.right {
  justify-content: end;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-active .drawer-panel,
.fade-leave-active .drawer-panel {
  transition: transform 0.25s var(--ease-in-out);
}

.fade-enter-from .drawer-panel,
.fade-leave-to .drawer-panel {
  transform: translateX(100%);
}

.drawer.left .drawer-panel {
  order: -1;
}

.drawer.left.fade-enter-from .drawer-panel,
.drawer.left.fade-leave-to .drawer-panel {
  transform: translateX(-100%);
}
</style>
