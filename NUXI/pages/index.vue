<script setup>
import { useRouter } from 'nuxt/app'
import { currentUser } from '~/deps/service';


// Redirect based on auth status
onMounted(async () => {
  if (currentUser.loggedin) {
    await navigateTo('/home')
  } else {
    await navigateTo('/auth')
  }
})
</script>

<template>
  <div>
  </div>
</template>