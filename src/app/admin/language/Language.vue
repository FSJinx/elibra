<template>
  <!-- <PageConstruction/> -->

  <div class="size-full flex-1 flex flex-col">
    <SectionHeader title="Languages" description="Manage languages used in catalog classifications and select options" icon="translate" />

    <div class="flex-1 flex flex-col p-5 gap-5 overflow-hidden">
      <Card>
        <CardBody class="flex justify-between items-center">
          <Input id="search-language" class="max-w-100" placeholder="Search for languages..." />

          <div class="flex items-center justify-end gap-2">
            <NewLanguage :languages="languages" />
          </div>
        </CardBody>
      </Card>

      <Table title="Languages Table" subtitle="List of all languages" :data-length="languages.data?.length ?? 0">
        <Thead>
          <tr>
            <Th>No.</Th>
            <Th class="text-left">Name</Th>
            <Th>Code</Th>
            <Th>Last Modified</Th>
            <Th class="max-w-50">Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="languages.data" :loading="languages.loading" cols="5">
          <tr v-for="(language, index) in languages.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="language.name" />
            <Td :data="language.code" />
            <Td :data="parse.dateTimeAgo(language.updated_at)" />
            <Td class="space-x-2">
              <EditLanguage :data="language" :languages="languages" />
              <Button size="sm" class="hover:text-danger" @click="remove(language)">Delete</Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewLanguage from '@/app/admin/language/modals/NewLanguage.vue'
import EditLanguage from '@/app/admin/language/modals/EditLanguage.vue'

const languages = useLanguagesStore()
const parse = useParser()
const pop = usePopup()

async function remove(lang: Language) {
  const confirm = await pop.confirm({ text: `Are you sure you want to delete ${lang.name} in your language list?` })

  if (confirm.isConfirmed) {
    try {
      pop.load()
      const res = await languages.destory(lang)
      pop.success(res.message)
    } catch (e: any) {
      throw e
    }
  }
}

onBeforeMount(async () => {
  await languages.fetch()
})
</script>

<style scoped></style>
