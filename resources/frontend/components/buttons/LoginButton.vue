<template>
  <Button class="text-primary px-4 py-2" @click="login?.open()" label="Login" color="primary" type="outline" />

  <LoginModal ref="login" />
</template>

<script setup>
import { ref } from 'vue'
import LoginModal from '../modals/LoginModal.vue'
import Button from './Button.vue'

const login = ref(null)
</script>

<style scoped></style>
