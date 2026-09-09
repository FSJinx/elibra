<template>
  <form class="flex flex-col gap-5 p-5 w-full max-w-7xl mx-auto" @submit.prevent="save">
    <!-- Basic Information -->
    <section class="overflow-hidden rounded-xl border border-border bg-background">
      <div class="border-b border-border px-5 py-4">
        <h2 class="text-xl font-semibold text-foreground">Basic Information</h2>

        <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic information for this catalog record.</p>
      </div>

      <div class="divide-y divide-border">
        <!-- Title -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label for="title" class="text-sm font-semibold text-foreground"> Title </label>

          <Input id="title" v-model="form.title" placeholder="Enter the title" />
        </div>

        <!-- Subtitle -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label for="subtitle" class="text-sm font-semibold text-foreground"> Subtitle </label>

          <Input id="subtitle" v-model="form.subtitle" placeholder="Enter the subtitle" enable-clear />
        </div>

        <!-- Description -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-start">
          <div>
            <label for="description" class="text-sm font-semibold text-foreground"> Description </label>

            <p class="mt-1 text-xs leading-relaxed text-foreground-secondary">A brief description displayed on the catalog record.</p>
          </div>

          <textarea id="description" v-model="form.description" rows="6" placeholder="Enter a description..." class="w-full resize-y rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground outline-none transition placeholder:text-foreground-secondary/60 focus:border-primary focus:ring-2 focus:ring-primary/10" />
        </div>

        <!-- Call Number -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label for="call-number" class="text-sm font-semibold text-foreground"> Call Number </label>

          <Input id="call-number" v-model="form.call_number" placeholder="e.g. QA76.76.C672" class="max-w-md" />
        </div>

        <!-- Publication Year -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label for="publication-year" class="text-sm font-semibold text-foreground"> Publication Year </label>

          <Input id="publication-year" v-model="form.publication_year" type="number" placeholder="e.g. 2008" class="max-w-40" />
        </div>
      </div>
    </section>

    <!-- Classification -->
    <section class="overflow-hidden rounded-xl border border-border bg-background">
      <div class="border-b border-border px-5 py-4">
        <h2 class="text-sm font-semibold text-foreground">Classification</h2>

        <p class="mt-0.5 text-xs text-foreground-secondary">Classification and ownership information for this record.</p>
      </div>

      <div class="divide-y divide-border">
        <!-- Item Type -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label class="text-sm font-semibold text-foreground"> Item Type </label>

          <Select id="item_type" v-model="form.item_type_id" class="max-w-md">
            <!-- Your Option components -->
          </Select>
        </div>

        <!-- Category -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label class="text-sm font-semibold text-foreground"> Category </label>

          <Select id="category" v-model="form.item_type_category_id" class="max-w-md">
            <!-- Your Option components -->
          </Select>
        </div>

        <!-- Branch -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label class="text-sm font-semibold text-foreground"> Branch </label>

          <Select id="branch" v-model="form.branch_id" class="max-w-md">
            <!-- Your Option components -->
          </Select>
        </div>

        <!-- Language -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <label class="text-sm font-semibold text-foreground"> Language </label>

          <Select id="language" v-model="form.language_id" class="max-w-md">
            <!-- Your Option components -->
          </Select>
        </div>
      </div>
    </section>

    <!-- Additional Information -->
    <section class="overflow-hidden rounded-xl border border-border bg-background">
      <div class="border-b border-border px-5 py-4">
        <h2 class="text-sm font-semibold text-foreground">Additional Information</h2>

        <p class="mt-0.5 text-xs text-foreground-secondary">Search and digital resource information.</p>
      </div>

      <div class="divide-y divide-border">
        <!-- Keywords -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-start">
          <div>
            <label for="keywords" class="text-sm font-semibold text-foreground"> Keywords </label>

            <p class="mt-1 text-xs text-foreground-secondary">Terms used when searching the catalog.</p>
          </div>

          <textarea id="keywords" v-model="form.keywords" rows="3" placeholder="e.g. programming, software engineering, clean code" class="w-full resize-y rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground outline-none transition placeholder:text-foreground-secondary/60 focus:border-primary focus:ring-2 focus:ring-primary/10" />
        </div>

        <!-- Electronic File -->
        <div class="grid grid-cols-1 gap-3 px-5 py-5 lg:grid-cols-[12rem_1fr] lg:items-center">
          <div>
            <p class="text-sm font-semibold text-foreground">Electronic File</p>

            <p class="mt-1 text-xs text-foreground-secondary">Optional digital copy associated with this record.</p>
          </div>

          <div class="flex items-center gap-3">
            <div v-if="form.electronic_file" class="flex min-w-0 items-center gap-3 rounded-lg border border-border bg-muted/50 px-3 py-2">
              <Icon icon="file-earmark" class="shrink-0 text-foreground-secondary" />

              <span class="truncate text-sm font-medium">
                {{ form.electronic_file }}
              </span>
            </div>

            <Button type="button" variant="default" left-icon="upload">
              {{ form.electronic_file ? 'Replace' : 'Upload' }}
            </Button>
          </div>
        </div>
      </div>
    </section>

    <!-- Form Actions -->
    <div class="sticky bottom-0 z-10 flex items-center justify-end gap-2 rounded-b-xl border border-border bg-background/95 px-5 py-4 backdrop-blur">
      <Button type="button" variant="default" @click="reset"> Cancel </Button>

      <Button type="submit" variant="primary" left-icon="check-lg"> Update Item </Button>
    </div>
  </form>
</template>

<script setup lang="ts">
interface CatalogForm {
  title: string
  subtitle: string
  description: string
  call_number: string
  publication_year: number | null
  electronic_file: string | null
  keywords: string
  item_type_id: number | string | null
  item_type_category_id: number | string | null
  branch_id: number | string | null
  language_id: number | string | null
}

const form = reactive<CatalogForm>({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  publication_year: null,
  electronic_file: null,
  keywords: '',
  item_type_id: null,
  item_type_category_id: null,
  branch_id: null,
  language_id: null,
})

function save() {
  // API update
}

function reset() {
  // Restore original item values
}
</script>
