<template>
  <div class="">
    <h1 class="section-title">Default</h1>
    <div class="section">
      <Button variant="primary">Primary</Button>
      <Button variant="secondary">Secondary</Button>
      <Button variant="info">Info</Button>
      <Button variant="success">Success</Button>
      <Button variant="danger">Danger</Button>
      <Button variant="warning">Warning</Button>
      <Button variant="restore">Restore</Button>
      <Button>Text</Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">Button as Link</h1>
    <div class="section">
      <Button as="router-link" :to="{ name: 'home' }" variant="primary">Primary</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="secondary">Secondary</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="info">Info</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="success">Success</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="danger">Danger</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="warning">Warning</Button>
      <Button as="router-link" :to="{ name: 'home' }" variant="restore">Restore</Button>
      <Button as="router-link" :to="{ name: 'home' }">Text</Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">Loading</h1>
    <div class="section">
      <Button loading variant="primary">Primary</Button>
      <Button loading variant="secondary">Secondary</Button>
      <Button loading variant="info">Info</Button>
      <Button loading variant="success">Success</Button>
      <Button loading variant="danger">Danger</Button>
      <Button loading variant="warning">Warning</Button>
      <Button loading variant="restore">Restore</Button>
      <Button loading>Text</Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">Icon Only</h1>
    <div class="section">
      <Button variant="primary"> <Icon icon="search"></Icon> </Button>
      <Button variant="secondary"> <Icon icon="search"></Icon> </Button>
      <Button variant="info"> <Icon icon="search"></Icon> </Button>
      <Button variant="success"> <Icon icon="search"></Icon> </Button>
      <Button variant="danger"> <Icon icon="search"></Icon> </Button>
      <Button variant="warning"> <Icon icon="search"></Icon> </Button>
      <Button variant="restore"> <Icon icon="search"></Icon> </Button>
      <Button> <Icon icon="search"></Icon> </Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">With Left Icon</h1>
    <div class="section">
      <Button left-icon="search" variant="primary">Search</Button>
      <Button left-icon="search" variant="secondary">Search</Button>
      <Button left-icon="search" variant="info">Search</Button>
      <Button left-icon="search" variant="success">Search</Button>
      <Button left-icon="search" variant="danger">Search</Button>
      <Button left-icon="search" variant="warning">Search</Button>
      <Button left-icon="search" variant="restore">Search</Button>
      <Button left-icon="search">Search</Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">With Right Icon</h1>
    <div class="section">
      <Button right-icon="search" variant="primary">Search</Button>
      <Button right-icon="search" variant="secondary">Search</Button>
      <Button right-icon="search" variant="info">Search</Button>
      <Button right-icon="search" variant="success">Search</Button>
      <Button right-icon="search" variant="danger">Search</Button>
      <Button right-icon="search" variant="warning">Search</Button>
      <Button right-icon="search" variant="restore">Search</Button>
      <Button right-icon="search">Search</Button>
    </div>
  </div>
  <div class="">
    <h1 class="section-title">Disabled</h1>
    <div class="section">
      <Button disabled variant="primary">Primary</Button>
      <Button disabled variant="secondary">Secondary</Button>
      <Button disabled variant="info">Info</Button>
      <Button disabled variant="success">Success</Button>
      <Button disabled variant="danger">Danger</Button>
      <Button disabled variant="warning">Warning</Button>
      <Button disabled variant="restore">Restore</Button>
      <Button disabled>Text</Button>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.section-title {
  font-size: var(--text-xl);
  font-weight: var(--font-weight-semibold);
}

.section {
  display: flex;
  flex-wrap: wrap;
  gap: calc(var(--spacing) * 3);
  margin-block: 1.5rem;
}
</style>
