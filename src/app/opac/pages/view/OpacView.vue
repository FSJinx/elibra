<template>
  <div class="min-h-screen w-full max-w-7xl mx-auto bg-background border-x border-border shadow">
    <!-- Header -->
    <div class="sticky top-0 z-10 flex items-center px-5 py-5 border-b border-border bg-background/95 backdrop-blur">
      <Button icon="arrow-left" data-title="Return" variant="text" @click="$router.back()" />

      <div class="ml-4 min-w-0">
        <p class="text-xs text-foreground-secondary uppercase tracking-wider">Online Public Access Catalog</p>

        <h5 class="font-medium text-lg sm:text-2xl truncate">Now Viewing: {{ opac.currentData?.title }}</h5>
      </div>
    </div>

    <!-- Main Content -->
    <main class="p-6 sm:p-10">
      <!-- Book Overview -->
      <section class="grid grid-cols-1 lg:grid-cols-[1fr_3fr] gap-5 lg:gap-12">
        <!-- Cover -->
        <div class="flex flex-col items-center gap-5 justify-center lg:justify-start shrink-0">
          <div class="relative w-full h-max rounded-2xl border-2 border-border shadow-lg overflow-hidden group contain-content">
            <img :src="opac.currentData?.cover_media?.file_path ? `${storageRoute}/${opac?.currentData.cover_media?.file_path}` : default_book" :alt="`${opac.currentData?.title ?? 'Item'} cover`" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" />

            <!-- Availability Badge -->
            <div class="absolute top-0 left-0 size-full group hover:bg-backdrop/50 transition-all duration-300">
              <span class="inline-flex items-center gap-2 px-3 py-1.5 m-5 rounded-full bg-background/95 backdrop-blur text-sm font-medium shadow opacity-0 group-hover:opacity-100 transition-all duration-300">
                <span class="size-2 rounded-full bg-green-500"></span>
                {{ opac.currentData?.status ?? 'Available' }}
              </span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-center flex-wrap gap-3">
            <Button left-icon="heart"> Add to Favorites </Button>
            <Button left-icon="share-fill" variant="text"> Share </Button>
          </div>
        </div>

        <!-- Information -->
        <div class="flex flex-col">
          <!-- Title -->
          <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2 mb-3">
              <Status :variant="opac.currentData?.item_type?.slug">{{ opac.currentData?.item_type?.name }}</Status>

              <span v-if="opac.currentData?.item_type_category" class="px-2.5 py-1 rounded-md bg-background border border-border text-xs"> {{ opac.currentData.item_type_category?.name }} </span>
            </div>

            <Title class="text-4xl!">{{ opac?.currentData?.title }}</Title>
            <p class="mt-2 text-foreground-secondary">{{ opac.currentData?.subtitle ?? 'This item has no secondary title' }}</p>

            <p v-if="opac.currentData?.authors?.length" class="mt-4 text-sm">
              by
              <span class="font-medium text-primary"> {{ opac.currentData.authors.join(', ') }} </span>
            </p>
          </div>

          <!-- Quick Metadata -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-7">
            <Info class="p-4 border border-border rounded-lg" label="Year Published" :value="opac.currentData?.publication_year" />
            <Info class="p-4 border border-border rounded-lg" label="ISBN/ISSN" :value="opac.currentData?.isbn_issn" />
            <Info class="p-4 border border-border rounded-lg" label="Call Number" :value="opac.currentData?.call_number" />
            <Info class="p-4 border border-border rounded-lg" label="Language" :value="opac.currentData?.language?.name" />
          </div>
        </div>
      </section>

      <!-- Divider -->
      <div class="border-t border-border my-10"></div>

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-foreground-secondary">
        <button class="px-2 py-1 cursor-pointer hover:text-primary" size="sm" variant="text" v-for="item in pages" @click="activePage = item.path" :class="[{ 'text-primary': activePage === item.path }]">{{ item.name }}</button>
      </div>

      <!-- Details -->
      <OpacDetails :data="opac.currentData" v-if="activePage === ''" />

      <!-- Copies -->
      <OpacCopies :data="opac.currentData" v-if="activePage === 'copies'" />

      <!-- Footer Note -->
      <div class="mt-10 p-5 rounded-xl bg-primary/5 border border-primary/10">
        <div class="flex gap-3">
          <span class="shrink-0 size-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <Icon icon="info-circle" />
          </span>

          <div>
            <p class="font-medium text-sm">Need help finding this item?</p>

            <p class="text-sm text-foreground-secondary mt-1">Ask a librarian at {{ opac.currentData?.branch ?? 'the library' }} for assistance locating this material.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import OpacCopies from '@/app/opac/pages/view/OpacCopies.vue'
import OpacDetails from '@/app/opac/pages/view/OpacDetails.vue'
import default_book from '@/assets/images/default_book.png'

const opac = opacStore()

const activePage = ref('')
const pages = ref([
  { name: 'Details', path: '' },
  { name: 'Copies', path: 'copies' },
])
</script>

<style scoped></style>
