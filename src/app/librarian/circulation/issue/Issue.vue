<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check In & Check Out" description="Check in or out materials in this library" icon="journal-plus"></SectionHeader>

    <div class="flex-1 flex items-start gap-5 p-5">
      <Card class="w-150 shrink-0">
        <CardHeader>Loan Form</CardHeader>
        <CardBody>
          <Form class="flex flex-col gap-5" id="issue-form" @submit="">
            <Control id="patron-id" col required>
              <Label>Patron</Label>
              <Input type="text" placeholder="Enter patron's ID Number or EBC" auto-focus />
            </Control>
            <Control id="item-accession" col required>
              <Label>Item</Label>
              <Input type="text" placeholder="Enter item's accession code or barcode number" />
            </Control>
          </Form>
        </CardBody>
        <CardFooter class="flex justify-end gap-2">
          <Button form="issue-form" type="submit" variant="success">Check</Button>
        </CardFooter>
      </Card>

      <Card class="w-full">
        <CardHeader>
          Loan Information
          <p class="text-sm text-muted-foreground">Preview of patron and item to loan.</p>
        </CardHeader>

        <CardBody v-if="!preview">
          <div class="text-center p-5">No item on the preview.</div>
        </CardBody>

        <CardBody class="flex flex-col gap-5" v-else>
          <Title :level="1">Loan's Preview</Title>

          <h5 class="uppercase text-muted-foreground font-semibold">Patron's Information</h5>
          <div class="flex items-start gap-3 border border-border p-5 rounded-2xl">
            <!-- Patron's Photo -->
            <div class="shrink-0 size-30 max-w-30 border border-border rounded-full overflow-hidden shadow">
              <img :src="user" alt="" class="max-w-full" loading="lazy" />
            </div>
            <!-- Patron's Information -->
            <div class="flex flex-col p-2">
              <Title :level="2" class="mb-2">Juan Dela Cruz</Title>

              <p><span class="font-semibold">Patron Type:</span> Student</p>
              <p><span class="font-semibold">Department:</span> CCSICT</p>
              <p><span class="font-semibold">Program:</span> College of Computing Studies, Information, and Communication Technology</p>
            </div>
          </div>

          <h5 class="uppercase text-muted-foreground font-semibold">Item's Information</h5>
          <div class="flex items-start gap-3 border border-border p-5 rounded-2xl">
            <!-- Item's Photo -->
            <div class="shrink-0 w-30 max-w-30 border border-border rounded-xl overflow-hidden shadow">
              <img :src="book" alt="" class="max-w-full" loading="lazy" />
            </div>
            <!-- Item's Information -->
            <div class="flex flex-col p-2">
              <Title :level="2">e-Libra: A Centralized Web-based Library Management and Resource Monitoring System for the Isabela State University</Title>
              <p>Patron Type: Student</p>
              <p>Department: CCSICT</p>
              <p>Program: College of Computing Studies, Information, and Communication Technology</p>
            </div>
          </div>
        </CardBody>
        <CardFooter class="flex items-center justify-end gap-2" v-if="preview">
          <Button form="" type="submit" variant="danger">Cancel</Button>
          <Button form="" type="submit" variant="success" @click="confirmLoan">Confirm</Button>
        </CardFooter>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import book from '@/assets/images/default_book.png'
import user from '@/assets/images/user.png'

const pop = usePopup()

const preview = ref(null)

async function confirmLoan() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to proceed with the loan?' })

  if (confirm.isConfirmed) {
  }
}

async function cancelLoan() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to cancel the loan?' })

  if (confirm.isConfirmed) {
  }
}
</script>

<style scoped></style>
