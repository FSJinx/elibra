<template>
  <main class="relative flex-1 flex flex-col overflow-hidden bg-background border border-border rounded-xl">
    <!-- Main Body Header -->
    <!-- <div class="sticky top-0 flex flex-col gap-3 p-5 pb-0 bg-background">
      <div class="flex md:items-center flex-col md:flex-row gap-5">
        <div class="flex items-center gap-3">
          <Button variant="default" @click="system.toggleSidebar" class="group hover:drop-shadow">
            <Icon :icon="system.sidebar ? 'box-arrow-in-left' : 'box-arrow-in-right'" class="transition-all duration-300" />
          </Button>
          <img :src="images.isu" alt="" class="size-10" />
          <div class="inline-block">
            <h5 class="font-semibold text-xl">Isabela State University</h5>
            <p class="text-sm font-normal">{{ subHeading }}</p>
          </div>
        </div>

        
      </div>

    </div> -->

    <!-- Main Body Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <div class="flex-1 flex flex-col rounded-xl p-5 overflow-y-auto scrollbar-none transition-all duration-200">
        <router-view />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const system = systemStore()
const parse = useParser()

const subHeading = computed(() => {
  return `University Library, ${parse.toCapital(auth.user?.role || 'Unknown')}`
})
</script>

<style scoped></style>
