<script setup>
import "/styles.css";
import "@vuepic/vue-datepicker/dist/main.css";
import { store } from "@/deps/service.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
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
        <Loading
            v-model:active="store.isLoading"
            :can-cancel="false"
            :is-full-page="true"
            :color="'#3359E1'"
        />
    </div>
</template>

<style>
.page-enter-active,
.page-leave-active {
    transition: all 0.3s;
}
.page-enter-from,
.page-leave-to {
    opacity: 0;
    filter: blur(0.5rem);
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    z-index: 9999;
}
</style>
