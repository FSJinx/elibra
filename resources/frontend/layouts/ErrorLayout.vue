<template>
  <DefaultLayout>
    <section class="flex items-center justify-center w-full h-screen pt-18">
      <div class="relative flex flex-col items-center w-[60%] h-[80%]">
        <div v-for="(corner, index) in corners" :key="index" class="absolute border-primary" :style="{ ...corner.position, width: `${cornerLength}px`, height: `${cornerLength}px`, borderTopWidth: corner.borders.top ? `${borderSize}px` : '0', borderRightWidth: corner.borders.right ? `${borderSize}px` : '0', borderBottomWidth: corner.borders.bottom ? `${borderSize}px` : '0', borderLeftWidth: corner.borders.left ? `${borderSize}px` : '0' }" />
        <div class="flex flex-col gap-3 h-full w-[80%] items-center justify-center text-center">
          <slot />
          <div class="flex gap-3 mt-5">
            <AnchorButton type="outline" label="Home" icon="Home" color="primary" link="Home" />
            <AnchorButton type="solid" :label="lastRoute.name || 'Go Back'" icon="ArrowLeft" color="primary" :link="lastRoute.name" v-if="lastRoute.name && lastRoute.name !== 'Home'" />
          </div>
        </div>
      </div>
    </section>
  </DefaultLayout>
</template>

<script setup>
import { ref } from 'vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AnchorButton from '@/components/buttons/AnchorButton.vue'
import { lastRoute } from '@/router'

const borderSize = 15
const cornerLength = 80

const corners = ref([
  {
    position: { top: 0, left: 0 },
    borders: {
      top: true,
      left: true,
    },
  },
  {
    position: { top: 0, right: 0 },
    borders: {
      top: true,
      right: true,
    },
  },
  {
    position: { bottom: 0, left: 0 },
    borders: {
      bottom: true,
      left: true,
    },
  },
  {
    position: { bottom: 0, right: 0 },
    borders: {
      bottom: true,
      right: true,
    },
  },
])
</script>
