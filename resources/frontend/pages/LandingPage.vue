<template>
  <DefaultLayout>
    <HeroSection />
    <AnnouncementSection />
    <FeatureSection />
    <FeatureBelt />
    <AcaRepo />
    <FeatureBelt />
    <OnlineResourcesSection />
    <FeatureBelt />
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AcaRepo from '@/sections/landing/AcaRepo.vue'
import AnnouncementSection from '@/sections/landing/AnnouncementSection.vue'
import FeatureBelt from '@/sections/landing/FeatureBelt.vue'
import FeatureSection from '@/sections/landing/FeatureSection.vue'
import HeroSection from '@/sections/landing/HeroSection.vue'
import OnlineResourcesSection from '@/sections/landing/OnlineResourcesSection.vue'
</script>
