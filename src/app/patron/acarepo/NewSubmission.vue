<template>
  <Button class="ml-auto" variant="primary" left-icon="plus-lg" @click="open">Submit Manuscript</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="AcaRepo: Academic Repository" subtitle="Terms & Conditions for Submission" icon="mortarboard" />
    <ModalBody class="p-5">
      <Form class="flex flex-col gap-4 text=[0.80rem]" id="condition-form" @submit="toForm">
        <div>
          <Title :level="3">I. Submission Requirements</Title>
          <ol class="list-disc pl-6 space-y-1">
            <li>The manuscript must be the final, approved version, signed off by the adviser and panel.</li>
            <li>Submit a PDF copy of the full text, plus a signed Author Agreement (non-exclusive license).</li>
            <li>Include the title, all authors, adviser, degree/program, year, abstract, and keywords.</li>
            <li>Remove or request permission for third-party materials (figures, images, datasets) not owned by the author.</li>
            <li>Confidential or sensitive data (personal information, trade secrets) must be removed or masked before submission.</li>
          </ol>
        </div>

        <div>
          <Title :level="3">II. Author Rights and Responsibilities</Title>
          <ol class="list-decimal pl-6 space-y-1">
            <li>The author retains copyright over the manuscript.</li>
            <li>By submitting, the author grants [Institution] a non-exclusive, royalty-free license to store, preserve, and make the work available through e-Libra for educational and research purposes.</li>
            <li>The author declares the work is original, properly cited, and does not infringe the rights of others.</li>
            <li>The author may request an embargo (restricted access for a set period) by submitting a written request to the Library.</li>
          </ol>
        </div>

        <div>
          <Title :level="3">III. Access and Fair Use</Title>
          <ol class="list-decimal pl-6 space-y-1">
            <li>Manuscripts are available for private study, research, and education, consistent with fair use under Section 185 of the Intellectual Property Code of the Philippines (RA 8293).</li>
            <li>Users may quote or cite portions of a manuscript with proper attribution to the author and source.</li>
            <li>Users may not reproduce, distribute, sell, or republish a manuscript in whole or in substantial part without the author's written permission.</li>
            <li>Users may not remove, alter, or obscure copyright notices or credits.</li>
            <li>Plagiarism, including copying without attribution or submitting another's work as one's own, is a violation and subject to institutional disciplinary action.</li>
          </ol>
        </div>

        <div>
          <Title :level="3">IV. Library Responsibilities</Title>
          <ol class="list-decimal pl-6 space-y-1">
            <li>The Library will preserve manuscripts and display the copyright notice and the author's name on every record.</li>
            <li>The Library may restrict access to a manuscript pending an embargo or a copyright or privacy concern.</li>
            <li>Personal information in submissions is handled in accordance with the Data Privacy Act of 2012 (RA 10173).</li>
            <li>Takedown requests from authors or rights holders will be reviewed and acted on promptly.</li>
          </ol>
        </div>

        <div class="mt-5 text-sm">
          <Checkbox v-model="agree" label="I have read and agreed to the conditions and policy for submitting my manuscipt to this library" required />
        </div>
      </Form>
    </ModalBody>
<ModalFooter class="gap-2">
      <Button @click="close" variant="danger">Cancel</Button>
      <Button form="condition-form" variant="success" type="submit">Proceed</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const agree = ref<boolean>(false)

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

function toForm() {
  close()
  nextTick(() => router.push({ name: 'patron.acarepo.form' }))
  //   return
}
</script>

<style scoped>
ol {
  font-size: 0.85rem;
  font-weight: 300;
  margin-top: 0.5rem;
}
</style>
