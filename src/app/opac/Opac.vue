<template>
  <div class="relative w-full max-w-[100rem] p-10 mx-auto">
    <!-- ISU Header -->
    <div class="flex flex-col items-center space-y-2 font-baskervville">
      <div class="flex items-center justify-center gap-2 mb-10">
        <img :src="isu" alt="" class="size-25 z-2 rounded-full" />
        <div class="absolute size-25 z-1 rounded-full bg-primary/25 blur-md"></div>
      </div>

      <p class="font-medium text-xl">The Online Public Access Catalog (OPAC) of the</p>
      <div class="text-5xl uppercase font-semibold text-green-700 tracking-tight"><span class="text-[125%]">I</span>sabela <span class="text-[125%]">S</span>tate <span class="text-[125%]">U</span>niversity</div>
    </div>

    <!-- Search Box Here -->
    <form class="flex items-center justify-center w-full p-5 gap-2">
      <Button size="lg" icon="funnel-fill" class="px-5">
        <span class="hidden sm:inline font-normal">Filters</span>
      </Button>
      <Input id="search" placeholder="Search title, author, or call number..." size="lg" class="max-w-2xl" v-model="search.query" />
      <Button size="lg" icon="search" variant="primary" class="px-5"> </Button>
      <!-- <span class="hidden sm:inline font-normal">Search</span> -->
    </form>

    <!-- Search Preview Here -->
    <div class="space-y-2 p-5" v-if="search.query">
      <h1 class="text-primary font-semibold uppercase tracking-wider">Showing results for</h1>
      <p class="font-serif text-3xl italic">"{{ search.query }}"</p>
    </div>

    <OpacDiscover v-else />

    <!-- Search Result Here -->
    <div class="flex flex-col space-y-2 p-5" v-if="search.query && data">
      <template v-for="(item, index) in libraryData" :key="index">
        <Card>
          <div class="grid grid-cols-4">
            {{ index }}
          </div>
        </Card>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import { libraryData } from '@/app/opac/opac_dummy_data'
import OpacDiscover from '@/app/opac/OpacDiscover.vue'
import isu from '@/assets/images/isu.png'

const route = useRoute()
const data = ref([])

const search = reactive({
  query: (route.query.search as string) ?? '',
})
</script>

<style scoped></style>
