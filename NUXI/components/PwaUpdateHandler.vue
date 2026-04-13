<!-- components/PwaUpdateHandler.vue -->
<template>
  <div v-if="updateAvailable" class="update-notification">
    A new version is available!
    <button @click="updateServiceWorker">Update now</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRegisterSW } from 'virtual:pwa-register/vue'

const { updateServiceWorker } = useRegisterSW()
const updateAvailable = ref(false)

onMounted(() => {
  const { needRefresh } = useRegisterSW({
    immediate: true,
    onRegisteredSW(swUrl, r) {
      r && setInterval(async () => {
        if (!(!r.installing && navigator.serviceWorker.controller)) {
          return
        }
        
        if (typeof r.update === 'function') {
          const resp = await r.update()
          if (resp && resp.waiting) {
            updateAvailable.value = true
          }
        }
      }, 20 * 60 * 1000) // Check every 20 minutes
    },
    onNeedRefresh() {
      updateAvailable.value = true
    }
  })
})
</script>