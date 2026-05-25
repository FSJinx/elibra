<template>
  <main class="relative h-auto">
    <HeroSection />
    <AnnouncementSection />
    <FeatureSection />
    <FeatureBelt />
    <AcaRepo />
    <FeatureBelt />
    <OnlineResourcesSection />
    <FeatureBelt />
  </main>
</template>

<script setup>
import HeroSection from '@/components/public/landing/HeroSection.vue'
import AnnouncementSection from '@/components/public/landing/AnnouncementSection.vue'
import FeatureSection from '@/components/public/landing/FeatureSection.vue'
import FeatureBelt from '@/components/public/landing/FeatureBelt.vue'
import AcaRepo from '@/components/public/landing/AcaRepo.vue'
import OnlineResourcesSection from '@/components/public/landing/OnlineResourcesSection.vue'
</script>
