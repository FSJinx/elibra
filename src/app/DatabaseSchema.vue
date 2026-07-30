<template>
  <div class="mx-auto max-w-7xl p-5">
    <h1 class="font-bold text-2xl mb-5">Database Schema for Items</h1>
    <div class="flex gap-5">
      <section class="rounded-lg border border-slate-200 bg-white shadow-sm min-w-xl h-max">
        <div class="flex items-center justify-between gap-3 p-5 border-b border-slate-200">
          <h1 class="text-lg font-semibold text-slate-900">Item Field</h1>
          <span class="text-sm text-slate-500">{{ commonFields.length }} fields</span>
        </div>
        <div v-if="commonFields.length" class="flex flex-wrap gap-2 p-5">
          <span v-for="field in commonFields" :key="field" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm text-slate-700">
            {{ field }}
          </span>
        </div>
        <p v-else class="text-sm text-slate-500">No common fields found.</p>
      </section>
      <div class="space-y-5">
        <section v-for="group in groupedItems" :key="group.labels.join('-')" class="rounded-lg border border-slate-200 bg-white shadow-sm">
          <div class="flex items-center justify-between gap-3 p-5 border-b border-slate-200">
            <h1 class="text-lg font-semibold text-slate-900">{{ group.labels.join(', ') }}</h1>
            <span class="text-sm text-slate-500">{{ group.fields.length }} fields</span>
          </div>
          <div v-if="group.fields.length" class="flex flex-wrap gap-2 p-5">
            <span v-for="field in group.fields" :key="field" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm text-slate-700">
              {{ field }}
            </span>
          </div>
          <p v-else class="text-sm text-slate-500">No fields defined.</p>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const schemaSections = [
  {
    label: 'Academic',
    fields: ['title', 'call_number', 'language', 'category', 'abstract', 'institution', 'program', 'year_published', 'subjects', 'location', 'keywords'],
  },
  {
    label: 'General',
    fields: ['title', 'call_number', 'edition', 'author', 'author_number', 'physical_description', 'accession', 'language', 'category', 'isbn', 'status', 'location', 'publisher', 'year_published', 'copyright', 'keywords'],
  },
  {
    label: 'Filipiniana',
    fields: ['title', 'call_number', 'language', 'category', 'isbn', 'status', 'location', 'publisher', 'year_published', 'keywords'],
  },
  // {
  //   label: 'Reference',
  //   fields: [],
  // },
  // {
  //   label: 'Serials',
  //   fields: ['title'],
  // },
]

const commonFields = computed(() => {
  const validFields = schemaSections.filter((section) => section.fields.length > 0)

  if (!validFields.length || validFields.some((section) => section.fields.length === 0)) {
    return []
  }

  return validFields.reduce<string[]>(
    (sharedFields, section) => {
      return sharedFields.filter((field) => section.fields.includes(field))
    },
    [...validFields[0].fields],
  )
})

const groupedItems = computed(() => {
  const groups = new Map<string, { labels: string[]; fields: string[] }>()

  for (const section of schemaSections) {
    const remainingFields = section.fields.filter((field) => !commonFields.value.includes(field))

    if (!remainingFields.length) {
      continue
    }

    const key = remainingFields.join('|')
    const existingGroup = groups.get(key)

    if (existingGroup) {
      existingGroup.labels.push(section.label)
      continue
    }

    groups.set(key, {
      labels: [section.label],
      fields: remainingFields,
    })
  }

  return Array.from(groups.values())
})
</script>

<style scoped></style>
