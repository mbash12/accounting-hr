<template>
    <main class="bg-white overflow-auto">
      <div class="flex flex-col py-8 gap-4 items-center h-full px-6 max-w-xl mx-auto w-full">
        <div class="w-220px h-260px mb-10 flex items-center justify-center">
          <img src="/auth.png" alt="" class="h-full object-contain" />
        </div>
        <div class="flex flex-col gap-5 text-left w-full mb-6 px-2">
          <span class="font-regular text-26px text-[#404040]">Login</span>
          <span class="text-[#565656] font-light text-16px">Silahkan login terlebih dahulu</span>
        </div>
        <Button
          class="w-full flex items-center justify-center h-60px gap-4  flex-shrink-0"
          variant="red"
          @click="navigateTo('/auth/login')"
        >
          <div class="bg-[#FFEDED] rounded-full p-1.5 w-35px h-35px flex items-center justify-center">
            <Icon icon="clarity:email-line" width="1.5rem" height="1.5rem" />
          </div>
          Masuk Dengan Email
        </Button>

        <span
          class="mt-4 text-[#EE1F25] cursor-pointer"
          @click="$router.push('/auth/forgot')"
          >Forgot Password</span
        >
        <span class="flex-1"></span>
        <span class="font-light text-14px text-[#919294] pb-10">Copyright © PT Pelangi Sentral Kreasi</span>
      </div>
    </main>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  import { Icon } from '@iconify/vue';
  import { checkLoggedin } from '@/deps/service';
  
  onMounted(async () => {
    try {
      const user = await checkLoggedin();
      if (user?.loggedin) {
        await navigateTo('/home');
      }
    } catch (err) {
      console.error("Error checking current user:", err);
    }
  });
  </script>