<template>
  <div class="flex items-center w-full py-3 px-4 bg-background border-b border-border">
    <!-- Right Side -->
    <Button variant="default" @click="system.toggleSidebar" class="group shadow hover:shadow-md mr-5" :data-title="system.sidebar ? 'Close Sidebar' : 'Open Sidebar'">
      <Icon :icon="system.sidebar ? 'x-lg' : 'list'" class="transition-all duration-300" />
    </Button>
    <img :src="images.isu" alt="" class="size-10" />
    <div class="inline-flex flex-col ml-4">
      <h5 class="font-semibold text-lg">Isabela State University</h5>
      <p class="text-sm font-normal">{{ subHeading }}</p>
    </div>

    <!-- Left Buttons -->
    <div class="flex items-center justify-end gap-1.5 ml-auto">
      <ManagementSearchButton />
      <Button icon="bell"></Button>
      <Button>
        <span class="grid place-content-center border border-border rounded-full size-7 bg-primary mr-1">
          <img :src="auth.user?.profile_picture" alt="" class="" v-if="auth.user?.profile_picture" />
          <Icon icon="person-circle" v-else />
        </span>
        <span class="shrink-0">{{ auth.user?.first_name }}</span>
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import ManagementSearchButton from '@/layouts/management/ManagementSearchButton.vue'

const auth = authStore()
const system = systemStore()
const parse = useParser()

const subHeading = computed(() => {
  return `University Library, ${parse.toCapital(auth.user?.role || 'Unknown')}`
})
</script>

<style scoped></style>
