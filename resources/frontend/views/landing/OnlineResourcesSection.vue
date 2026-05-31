<template>
  <section class="grid grid-cols-3 gap-5 p-13 bg-neutral-800 text-white">
    <div class="border-x-10 border-primary px-5 py-3 flex flex-col">
      <h1 class="font-bold text-3xl mb-2">Online Resources</h1>
      <p class="text-neutral-300">Certain online resources require login credentials. Kindly request access from the student assistants or the librarian.</p>
    </div>

    <a :href="source.link" class="ores flex items-center justify-center bg-white p-2 max-h-125 rounded text-black relative overflow-hidden" v-for="source in sources" :key="source.name">
      <div class="cover bg-black/75 absolute bottom-0 left-0 flex items-end opacity-0 w-full h-full overflow-hidden transition-height duration-200 text-white text-2xl font-bold text-center transform translate-x-full">
        <span class="w-full bg-primary overflow-hidden p-3">{{ source.name }}</span>
      </div>
      <img :src="source.img" :alt="source.name" class="max-h-full max-w-full" />
    </a>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const sources = ref([
  {
    name: 'DOST-PCAARRD',
    link: '#',
    img: 'https://www.pcaarrd.dost.gov.ph/images/2023/02/09/elibrary-03+white%20background%20(1)6.jpg',
  },
  {
    name: 'PHL Ched Connect',
    link: '#',
    img: 'https://phlconnect.ched.gov.ph/assets/img/phl-ched-logo.png',
  },
  {
    name: 'iG Library',
    link: '#',
    img: 'https://www.standardsinformation.co/wp-content/uploads/2024/01/iG-Library-Logo.png',
  },
  {
    name: 'EBSCO Host',
    link: '#',
    img: 'https://s3.amazonaws.com/libapps/accounts/109095/images/EBSCOhost.png',
  },
  {
    name: 'Philippine e-Lib',
    link: '#',
    img: 'https://elib.nlp-dev.up.edu.ph/assets/media/image/elib_new_logo.png',
  },
  {
    name: 'World Technologies',
    link: '#',
    img: 'https://www.igpublish.com/wp-content/uploads/2020/09/P-Worldtechnologies.png',
  },
  {
    name: 'Perlego',
    link: '#',
    img: 'https://upload.wikimedia.org/wikipedia/commons/d/db/Perlego_logo.png',
  },
  {
    name: 'Philippine E-Journals',
    link: '#',
    img: 'https://library.nddu.edu.ph/asset/img/pej.png',
  },
  {
    name: 'DepEd Manila e-Library',
    link: '#',
    img: 'https://library.isu.edu.ph/wp-content/uploads/2025/11/image_2025-11-18_091317104.png',
  },
  {
    name: '3G e-Learning',
    link: '#',
    img: 'https://3gsmartlibrary.com/img/logo.jpg',
  },
  {
    name: 'IEEE Advancing Technology for Humanity',
    link: '#',
    img: 'https://www.ieeesmc.org/2025-ieee-sar-hackathon/wp-content/uploads/sites/26/2025/09/ieee-logo-2-1024x602.png',
  },
])
</script>

<style scoped>
.ores:hover .cover {
  opacity: 1;
  transform: translateX(-100%);
}
</style>
