<template>
  <div class="flex flex-col h-full overflow-hidden">
    <div class="p-5 pb-0 space-y-2">
      <h2 class="flex items-center gap-3 text-2xl font-bold text-slate-900 leading-normal"><IconButton icon="ArrowLeft" name="Back" variant="default-hover" radius="pill" @click="router.push({ name: 'admin.campus' })" /> {{ currentCampus?.name }}</h2>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import IconButton from '@/components/ui/IconButton.vue'
import { useCampus } from '@/stores/campusCache'
import router from '@/router'

const route = useRoute()

const campus = useCampus()
const id = route.params.id

const currentCampus = computed(() => {
  if (id && campus.currentCampus?.id) {
    return campus.currentCampus
  }
})
</script>

<style scoped></style>
