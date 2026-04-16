<script setup>
import "/styles.css";
import "@vuepic/vue-datepicker/dist/main.css";

import PwaUpdateHandler from '@/components/PwaUpdateHandler.vue'


useHead({
  link: [
    { rel: 'manifest', href: '/user/manifest.json' }
  ]
})

</script>

<template>
    <div>
        <PwaUpdateHandler />
        <VitePwaManifest />
        <NuxtPwaManifest />
        <NuxtLayout>
            <NuxtPage />
        </NuxtLayout>
    </div>
</template>
