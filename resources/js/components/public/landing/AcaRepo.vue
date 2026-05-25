<template>
  <div class="flex flex-col w-full p-10">
    <h1 class="text-3xl font-extrabold mb-10 text-center text-primary">Manuscript Submission</h1>

    <div class="flex flex-col rounded-lg bg-primary/10 p-8 mb-10">
      <div class="flex font-bold gap-3 mb-5 items-center">
        <CircleAlert />
        <h3>Thesis Submission</h3>
      </div>
      <p class="text-neutral-600">Before submitting, please ensure you have obtained approval from your thesis adviser and that your work meets all university requirements. Submissions that do not meet the specified guidelines will be returned for revision.</p>
    </div>

    <div class="w-full flex flex-col">
      <h1 class="font-bold text-2xl">Submission Process</h1>
      <p class="text-neutral-600 mb-5">Please follow the steps below to submit your manuscript to our digital repository</p>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white shadow-md shadow-primary/20 rounded-lg p-5 flex flex-col gap-4 flex-1" v-for="(process, index) in processes" :key="process.title + index">
          <div class="flex items-start gap-5 p-2">
            <p class="text-4xl font-bold text-primary/50 w-10 mr-2">0{{ index + 1 }}</p>
            <div class="flex flex-col gap-2 mt-1.5">
              <h2 class="text-lg font-bold">{{ process.title }}</h2>
              <p>{{ process.description }}</p>
            </div>
          </div>

          <div class="flex flex-col gap-2 p-3" v-if="process.corrects && process.corrects.length > 0">
            <h1 class="font-bold text-primary">Do's</h1>
            <div class="flex items-start text-wrap" v-for="(correct, index) in process.corrects" :key="index">
              <CheckCheck class="text-secondary shrink-0 mr-3" :stroke-width="2.5" />
              <div class="">{{ correct }}</div>
            </div>
          </div>

          <div class="flex flex-col gap-2 p-3" v-if="process.wrong && process.wrong.length > 0">
            <h1 class="font-bold text-red-900">Don'ts</h1>
            <div class="flex items-start text-wrap" v-for="(correct, index) in process.wrong" :key="index">
              <X class="text-red-500 shrink-0 mr-3" :stroke-width="2.5" />
              <div class="">{{ correct }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckCheck, ChevronRight } from 'lucide-vue-next'
import { ref } from 'vue'

const processes = ref([
  {
    title: 'Prepare Your Manuscript',
    description: 'Ensure your thesis is complete, properly formatted in PDF format, and does not exceed 50MB.',
    corrects: ['PDF Format Only', 'Maximum File Size: 50MB', 'Include All Required Sections (Abstract, Introduction, Methodology, Results, Conclusion, References)', 'Check formatting guidelines provided by your department or university'],
  },
  {
    title: 'Gather Required Information',
    description: 'Have all necessary details ready including authors, adviser, program, and keywords.',
    corrects: ['Researcher Information (Name, Email, Affiliation)', 'Thesis Adviser Information (Name, Email, Affiliation)', 'Program/Department', 'Keywords for indexing and searchability'],
  },
  {
    title: 'Submit Manuscript',
    description: 'Fill out the submission form with all required information and upload your thesis PDF.',
    corrects: ['Complete all required fields', 'UPload your mnuscript in PDF format', 'Review all information', 'Submit'],
  },
  {
    title: 'Wait for Approval',
    description: 'Once submitted, your submission will be reviewed by the library stafff. You will receive an email notification regarding the status of your submission.',
    corrects: ['Library staff review', 'Format verification', 'Metadata validation', 'Publication to repository'],
  },
])
</script>
