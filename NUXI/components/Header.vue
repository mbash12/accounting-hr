<script setup>
const props = defineProps({
  title: {
    type: String,
  },
})
</script>
<template>
    <div
        class="px-4 py-2 items-center bg-[#F10A13] text-white text-sm flex h-55px gap-4 flex-shrink-0"
    >
        <svg
            width="25"
            height="25"
            viewBox="0 0 25 25"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            @click="$router.go(-1)"
        >
            <path
                d="M16.0156 5.46875L8.98438 12.5L16.0156 19.5312"
                stroke="white"
                stroke-width="2.34375"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>

        <span class="font-bold text-17px">{{ props.title }}</span>
    </div>
</template>
