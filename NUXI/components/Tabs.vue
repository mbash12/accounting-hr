<script setup>
import { Icon } from "@iconify/vue";

const props = defineProps({
    tab: {
        type: Number,
    },
});
const emit = defineEmits(["selected"]);
const changeTab = (i) => {
    emit("selected", i);
}
</script>
<template>
    <div
        class="flex px-4 h-full"
    >
        <div class="absolute w-[calc(100%-32px)]">
            <div
                class="w-1/2 h-2px absolute transition-all duration-300"
                :style="`${
                    props.tab === 1 ? 'left:0' : 'left:50%'
                }; box-shadow: 0px 30px 20px 0.5px red`"
            ></div>
        </div>

        <button
            class="flex flex-1 items-center h-full justify-center gap-2 transition-all duration-300"
            :class="props.tab === 1 ? 'text-red-500' : 'text-gray-500'"
            @click="changeTab(1)"
        >
            <Icon
                icon="solar:archive-up-minimlistic-bold-duotone"
                width="1.7rem"
                height="1.7rem"
            />
            <span class="text-xs font-semibold">Pengajuan</span>
        </button>
        <button
            class="flex flex-1 items-center h-full justify-center gap-2 transition-all duration-300"
            :class="props.tab === 0 ? 'text-red-500' : 'text-gray-500'"
            @click="changeTab(0)"
        >
            <Icon
                icon="solar:clipboard-check-bold-duotone"
                width="1.7rem"
                height="1.7rem"
            />
            <span class="text-xs font-semibold">Persetujuan</span>
        </button>
    </div>
</template>
