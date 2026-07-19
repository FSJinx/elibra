<template>
  <div class="">
    <div class="overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
        <div>
          <h2 class="text-2xl font-semibold text-slate-900">Academic Collection</h2>
          <p class="text-sm text-slate-500">Browse and manage the academic records in the library collection.</p>
        </div>

        <p class="text-sm text-slate-500">{{ academicRecords.length }} records</p>
      </div>

      <div class="flex items-center gap-2 px-5 py-3">
        <input name="academic-query" id="academic-query" type="text" class="w-md px-4 h-11 bg-slate-50 border border-slate-300 rounded-md" placeholder="Search by title, call number, researcher" />
        <eButton class="" variant="solid-hover" color="success">+ Create New></eButton>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left">
          <thead class="bg-slate-50">
            <tr class="text-center">
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 text-left">Title</th>
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Call Number</th>
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Updated</th>
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
              <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200 bg-white">
            <tr v-for="record in academicRecords" :key="record.id" class="hover:bg-slate-50 text-center">
              <td class="px-5 py-4 text-left space-y-1">
                <div class="font-medium text-slate-900">{{ record.title }}</div>
                <div class="text-sm text-slate-500">{{ record.description }}</div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ record.code }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ record.type }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ record.updatedAt }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold" :class="record.statusClass">
                  {{ record.status }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex justify-center gap-2">
                  <button type="button" class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Edit</button>
                  <button type="button" class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">View</button>
                  <button type="button" class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-2 text-sm font-medium text-rose-600 transition hover:border-rose-300 hover:bg-rose-50">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import eButton from '../../../components/my/Button.vue/index.js'
import Icon from '../../../components/my/Icon.vue/index.js'

const academicRecords = [
  {
    id: 1,
    title: 'Introduction to Information Science',
    description: 'Core reference material for library science students',
    code: 'ACA-101',
    department: 'Information Science',
    type: 'Textbook',
    updatedAt: '2026-07-01',
    status: 'Available',
    statusClass: 'bg-emerald-50 text-emerald-700',
  },
  {
    id: 2,
    title: 'Research Methods Handbook',
    description: 'Guide for academic research and citation practices',
    code: 'ACA-204',
    department: 'Research & Development',
    type: 'Handbook',
    updatedAt: '2026-06-22',
    status: 'Reserved',
    statusClass: 'bg-amber-50 text-amber-700',
  },
  {
    id: 3,
    title: 'Digital Library Systems',
    description: 'Systems overview for managing digital collections',
    code: 'ACA-315',
    department: 'Library Technology',
    type: 'Monograph',
    updatedAt: '2026-05-18',
    status: 'Available',
    statusClass: 'bg-emerald-50 text-emerald-700',
  },
]
</script>

<style scoped></style>
