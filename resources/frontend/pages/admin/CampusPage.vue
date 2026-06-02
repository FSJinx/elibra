<template>
  <section class="flex flex-col gap-3">
    <div class="flex items-center justify-between h-20 p-5 bg-white rounded-lg border border-gray-300">
      <div class="flex items-center gap-3">
        <!-- <Select label="Sort By" :options="selections" :model-value="sortBy" /> -->
        <select name="sort" id="sort" class="p-2 px-4 text-primary border border-primary rounded cursor-pointer">
          <option value="">Sort By</option>
          <option value="name">Name</option>
          <option value="created_at">Date Added</option>
        </select>
      </div>
      <div class="flex items-center gap-2">
        <input type="text" class="p-2 border border-primary rounded leading-0" placeholder="Search here..." />
        <Button type="soft" label="New Campus" icon="Plus" color="green" />
      </div>
    </div>
    <!-- Table -->
    <div class="border border-gray-300 rounded-md overflow-hidden">
      <div class="grid grid-cols-5 p-5 py-4 bg-neutral-50 border-b border-gray-300 text-secondary font-bold">
        <div class="col-span-2">Campus Name</div>
        <div class="text-center">Library Director</div>
        <div class="text-center">Status</div>
        <div class="text-center">Actions</div>
      </div>

      <div class="grid grid-cols-5 items-center p-5 py-4 bg-white hover:bg-gray-100 border-b border-gray-200">
        <div class="text-wrap col-span-2">Isabela State University Main Campus</div>

        <div class="text-center">Betsie M. Dela Cruz</div>

        <div class="text-center text-sm">
          <span class="p-2 px-4 bg-secondary/10 text-secondary font-bold rounded-md">Active</span>
        </div>

        <div class="text-center flex gap-2 justify-center">
          <Button type="soft" icon="Eye" color="purple" />
          <Button type="soft" icon="Pencil" color="blue" />
          <Button type="soft" icon="Trash" color="red" />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import Button from '@/components/buttons/Button.vue'
import Select from '@/components/inputs/Select.vue'
import { ref, watch } from 'vue'

const sortBy = ref(null)

const selections = [
  { label: 'Name', value: 'name' },
  { label: 'Date Added', value: 'created_at' },
]
</script>

<style scoped></style>
