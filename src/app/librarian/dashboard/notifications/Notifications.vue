<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Notifications" description="Manage system notices and alerts sent to patrons." icon="bell">
      <div class="flex items-center justify-end gap-1">
        <span class="flex items-center gap-3 text-sm mr-3">
          <Badge variant="danger">25</Badge>
          Unread Notifications
        </span>
        <Select id="filter-date" class="max-w-max">
          <Option value="">All Notifications</Option>
          <Option value="day">24 Hours</Option>
          <Option value="week">Past Week</Option>
          <Option value="month">Past Month</Option>
          <Option value="year">Past Year</Option>
        </Select>
        <Button icon="envelope-check" data-title="Mark all as read"></Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 -mt-5 gap-5">
      <Table :data-length="25"> </Table>
    </div>
  </div>
</template>
