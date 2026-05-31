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
import AcaRepo from '@/views/landing/AcaRepo.vue'
import AnnouncementSection from '@/views/landing/AnnouncementSection.vue'
import FeatureBelt from '@/views/landing/FeatureBelt.vue'
import FeatureSection from '@/views/landing/FeatureSection.vue'
import HeroSection from '@/views/landing/HeroSection.vue'
import OnlineResourcesSection from '@/views/landing/OnlineResourcesSection.vue'
</script>
