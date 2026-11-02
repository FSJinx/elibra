<template>
  <Button class="hover:text-primary" left-icon="bag" @click="open">Bag</Button>

  <Drawer ref="drawer">
    <DrawerHeader class="flex items-center">
      <Icon icon="bag" class="mr-3" />
      <span>Check Out Bag</span>
    </DrawerHeader>

    <DrawerBody>
      <div class="size-full text-muted-foreground text-center place-content-center" v-if="!model?.patron && !model?.accessions?.length">No items in the check out bag yet.</div>

      <div class="flex flex-col items-start gap-3" v-else>
        <div class="w-full flex items-center justify-between">
          <Title :level="2">Item Preview</Title>
          <Button size="sm" class="hover:text-danger" @click="clear">Clear Bag</Button>
        </div>
        <Card class="p-4!" v-for="item in model?.accessions">
          <CardBody class="flex items-start gap-3">
            <div class="max-w-15 border border-border rounded-lg overflow-hidden">
              <img :src="item?.item?.cover_media ? `${storageRoute}/${item?.item?.cover_media}` : book" alt="" class="" />
            </div>

            <div class="flex flex-col gap-3">
              <Title>{{ item?.item?.title }}</Title>
              <div class="grid grid-cols-2 gap-2 text-sm">
                <p class="text-foreground-secondary line-clamp-1">Accession Number:</p>
                <span class="text-foreground text-end">{{ item?.accession_number ?? 'Invalid' }}</span>
                <p class="text-foreground-secondary line-clamp-1">Status:</p>
                <span class="text-foreground text-end capitalize">{{ item?.status ?? 'Invalid' }}</span>
              </div>
            </div>
            <Button class="hover:text-danger ml-auto" icon="trash" size="sm" @click="remove(item)"></Button>
          </CardBody>
        </Card>
      </div>
    </DrawerBody>

    <DrawerFooter class="flex flex-col gap-5" v-if="model?.accessions?.length || model?.patron">
      <div class="preview flex flex-col gap-2">
        <Title :level="3">Borrowing Information</Title>

        <div class="grid grid-cols-2 gap-1 text-sm">
          <p class="text-foreground-secondary line-clamp-1">Name:</p>
          <span class="text-foreground text-end">{{ model?.patron?.user?.name?.full ?? 'No selected patron' }}</span>

          <p class="text-foreground-secondary line-clamp-1">ID Number:</p>
          <span class="text-foreground text-end">{{ model?.patron?.user?.username ?? 'No selected patron' }}</span>

          <p class="text-foreground-secondary line-clamp-1">Total items borrowing:</p>
          <span class="text-foreground text-end">{{ model?.accessions?.length ?? 'No items selected' }}</span>
        </div>

        <Form class="w-full" id="submit-borrow" @submit="submit">
          <Control id="borrow-loan-mode" col>
            <Label>Loan Mode</Label>

            <Select v-model="model.loan_mode_id" required>
              <Option value="" selected disabled>Select Loan Mode</Option>
              <Option :value="item.id" v-for="item in options.loan_modes">{{ item.name }}</Option>
            </Select>
          </Control>
        </Form>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <Button variant="danger" @click="close">Cancel</Button>
        <Button variant="success" form="submit-borrow" type="submit">Proceed</Button>
      </div>
    </DrawerFooter>
  </Drawer>
</template>

<script setup lang="ts">
import Drawer from '@/components/drawer/Drawer.vue'
import book from '@/assets/images/default_book.png'

const model = defineModel<Borrow>()
const emit = defineEmits(['clear'])

const pop = usePopup()
const options = optionStore()
const circulation = circulationStore()

const drawer = ref<InstanceType<typeof Drawer>>()

function open() {
  drawer.value?.open()
}

function close() {
  drawer.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Proceed checkout?' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await circulation.borrow(model.value)
      pop.success(res.message)
      nextTick()
      clear()
      close()
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}

async function clear() {
  const confirm = await pop.confirm({ text: 'Are you sure you want to clear the current bag?' })

  if (confirm.isConfirmed) {
    emit('clear')
    close()
  }
}

async function remove(item: Accession) {
  const confirm = await pop.confirm({ text: `Are you sure you want to remove ${item.item?.title} from the bag?` })

  if (confirm.isConfirmed && model.value?.accessions) {
    model.value.accessions = model.value?.accessions?.filter((i) => i.id !== item.id)
    pop.success('Removed successfully!')
  }
}
</script>

<style scoped></style>
