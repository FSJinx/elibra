<template>
  <div class="flex flex-col w-full">
    <div class="flex items-center p-5">
      <h1 class="text-2xl font-semibold">Academics</h1>

      <eButton class="bg-primary text-white ml-auto">
        <Icon icon="plus" size="small" />
        <span>Create New</span>
      </eButton>
    </div>

    <div class="border-b border-slate-300 p-3 pt-0">
      <div class="flex flex-1 items-center justify-between gap-2">
        <div class="flex items-center gap-2">
          <span class="p-2.5 border border-slate-300 bg-slate-100 rounded-md">
            <Icon icon="search" />
          </span>
          <input name="academic-query" id="academic-query" type="text" class="w-md px-4 h-11 bg-slate-50 border border-slate-300 rounded-md" placeholder="Search by title, call number, researcher" />
          <eButton class="bg-primary text-white" size="large">
            <Icon icon="Plus" size="small"></Icon>
            <span>Create New</span>
          </eButton>
        </div>

        <div class="flex items-center gap-2">
          <eButton class="bg-slate-100 border-slate-300">
            <Icon icon="FolderOpenDot" size="small"></Icon>
            <span>Drafts</span>
          </eButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import eButton from '../../../components/my/Button.vue/index.js'
import Icon from '../../../components/my/Icon.vue/index.js'
</script>

<style scoped></style>
