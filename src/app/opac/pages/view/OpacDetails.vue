<template>
  <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Description -->
    <div class="lg:col-span-2">
      <h2 class="text-2xl font-semibold mb-4">About this item</h2>

      <p class="leading-7 text-foreground-secondary">{{ data?.description ?? 'No description available.' }}</p>

      <!-- Subjects -->
      <div class="mt-7">
        <h3 class="font-semibold mb-3">Subjects</h3>

        <div class="flex flex-wrap gap-2">
          <Chip v-for="item in data?.keywords">{{ item }}</Chip>
        </div>
      </div>
    </div>

    <!-- Bibliographic Information -->
    <aside>
      <div class="rounded-xl border border-border p-5">
        <h3 class="font-semibold text-lg mb-5">Bibliographic Information</h3>

        <dl class="space-y-4 text-sm">
          <div>
            <dt class="text-foreground-secondary">ISBN</dt>

            <dd class="font-medium mt-1">{{ data?.isbn_issn ?? 'Not available' }}</dd>
          </div>

          <div>
            <dt class="text-foreground-secondary">Call Number</dt>

            <dd class="font-medium mt-1">{{ data?.call_number ?? 'Not available' }}</dd>
          </div>

          <div>
            <dt class="text-foreground-secondary">Classification</dt>

            <dd class="font-medium mt-1">{{ data?.item_type_category?.name ?? 'Not available' }}</dd>
          </div>

          <div>
            <dt class="text-foreground-secondary">Material Type</dt>

            <dd class="font-medium mt-1">{{ data?.item_type?.name ?? 'Not available' }}</dd>
          </div>

          <div>
            <dt class="text-foreground-secondary">Collection</dt>

            <dd class="font-medium mt-1">{{ data?.branch ?? 'Not available' }}</dd>
          </div>
        </dl>
      </div>
    </aside>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{ data: Opac | null }>()
</script>

<style scoped></style>
