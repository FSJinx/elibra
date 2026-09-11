<template>
  <div class="flex border-b border-border px-5 py-4 bg-background" :class="modal?.buttonDisabled">
    <!-- Header if it uses the default layout -->
    <div class="flex gap-3 w-full items-center mr-auto" v-if="useDefaultLayout">
      <div class="flex h-12 aspect-square bg-primary-soft rounded-lg text-primary" v-if="$slots.icon || icon">
        <slot v-if="$slots.icon" name="icon" />
        <Icon :icon="icon" v-if="icon" class="text-xl m-auto" />
      </div>
      <div class="flex flex-col">
        <h1 class="font-semibold text-xl">{{ title }}</h1>

        <p class="text-sm text-muted-foreground font-lighter" v-if="$slots.subtitle || subtitle">
          <slot v-if="$slots.subtitle" name="subtitle" />
          <span v-else>{{ subtitle }}</span>
        </p>
      </div>
    </div>

    <slot v-else />
  </div>
</template>

<script setup lang="ts">
interface DefaultLayoutProps {
  useDefaultLayout: true
  title: string
}

interface CustomLayoutProps {
  useDefaultLayout?: false
  title?: string
}

type Props = LayoutProps & {
  subtitle?: string
  icon?: any
}

type LayoutProps = DefaultLayoutProps | CustomLayoutProps

const props = defineProps<Props>()

const modal = inject<any>('modal')
</script>
