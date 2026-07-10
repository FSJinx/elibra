<template>
  <div class="flex flex-col w-full h-full bg-slate-50">
    <div class="flex flex-col gap-4 border-b border-slate-200 bg-white px-5 py-5 sm:flex-row sm:items-end sm:justify-between">
      <div class="space-y-1">
        <h1 class="text-2xl font-bold text-slate-900">Subscriptions</h1>
        <p class="max-w-2xl text-sm text-slate-500">Manage the university's subscriptions to online resources, journals, and digital services.</p>
      </div>

      <Button class="bg-primary text-white">+ Add New</Button>
    </div>

    <div class="flex-1 p-5">
      <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
          <div>
            <h2 class="text-base font-semibold text-slate-900">Subscription List</h2>
            <p class="text-sm text-slate-500">A quick view of active and pending subscriptions.</p>
          </div>
          <p class="text-sm text-slate-500">{{ subscriptions.length }} records</p>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200 text-left">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Subscription</th>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Provider</th>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Plan</th>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Renewal Date</th>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 text-right">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="subscription in subscriptions" :key="subscription.id" class="hover:bg-slate-50/80">
                <td class="px-5 py-4">
                  <div class="font-medium text-slate-900">{{ subscription.name }}</div>
                  <div class="text-sm text-slate-500">{{ subscription.description }}</div>
                </td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ subscription.provider }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ subscription.plan }}</td>
                <td class="px-5 py-4 text-sm text-slate-600">{{ subscription.renewalDate }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold" :class="subscription.statusClass">
                    {{ subscription.status }}
                  </span>
                </td>
                <td class="px-5 py-4">
                  <div class="flex justify-end gap-2">
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
  </div>
</template>

<script setup lang="ts">
import Button from '@/components/ui/Button.vue';

const subscriptions = [
  {
    id: 1,
    name: 'EBSCOhost Academic Search',
    description: 'Research database for journals and periodicals',
    provider: 'EBSCO Information Services',
    plan: 'Annual License',
    renewalDate: '2026-09-15',
    status: 'Active',
    statusClass: 'bg-emerald-50 text-emerald-700',
  },
  {
    id: 2,
    name: 'ProQuest Central',
    description: 'Multidisciplinary collection of scholarly content',
    provider: 'ProQuest',
    plan: 'Campus Access',
    renewalDate: '2026-11-02',
    status: 'Pending',
    statusClass: 'bg-amber-50 text-amber-700',
  },
  {
    id: 3,
    name: 'ScienceDirect',
    description: 'Full-text scientific and medical research platform',
    provider: 'Elsevier',
    plan: 'Institutional Plan',
    renewalDate: '2027-01-21',
    status: 'Active',
    statusClass: 'bg-emerald-50 text-emerald-700',
  },
]
</script>

<style scoped></style>
