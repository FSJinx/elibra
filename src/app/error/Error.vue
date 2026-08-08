<template>
  <Teleport to="body">
    <Transition name="popup">
      <!-- Alert Popup -->
      <div v-show="state.show" class="inset-0 fixed flex bg-backdrop h-dvh w-full cursor-pointer" @click="close()">
        <div class="popup-card flex flex-col justify-center items-center bg-background rounded-2xl m-auto w-125 p-10 text-center" @click.stop>
          <!-- Logo -->
          <div class="relative flex justify-center items-center size-20 bg-red-50 text-danger rounded-full m-5 text-2xl">
            <Logo />
          </div>

          <!-- Error Code -->
          <div class="relative flex justify-center items-center max-w-max text-lg p-2 font-bold tracking-wide text-danger uppercase" v-if="state.code">
            Error {{ state.code }}
            <div class="h-0.5 w-[50%] min-w-10 absolute bottom-0 bg-danger rounded-full"></div>
          </div>

          <!-- Main Body -->
          <div class="pt-5 p-5 space-y-3">
            <!-- Error Title -->
            <h1 class="text-2xl font-semibold capitalize" v-if="state.title">{{ state.title }}</h1>

            <Badge variant="danger" v-if="state.path">Path: {{ state.path }}</Badge>

            <!-- Error Message -->
            <p class="text-muted" v-if="state.message">{{ state.message }}</p>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
const { state, close } = useError()
</script>

<style scoped>
.popup-enter-active,
.popup-leave-active {
  transition: opacity 0.2s ease;
}

.popup-leave-active {
  transition-delay: 0.05s;
}

.popup-enter-from,
.popup-leave-to {
  opacity: 0;
}

.popup-enter-active .popup-card,
.popup-leave-active .popup-card {
  transition:
    transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
    opacity 0.2s ease;
}

.popup-enter-from .popup-card,
.popup-leave-to .popup-card {
  opacity: 0;
  transform: scale(0.5);
}
</style>
