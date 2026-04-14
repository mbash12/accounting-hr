<script setup>
import { reactive, ref } from "vue";
import { Icon } from "@iconify/vue";
import { onClickOutside } from "@vueuse/core";

const state = reactive({
    open: false,
});
const onClick = () => {
    state.open = false;
};

const target = ref(null);
onClickOutside(target, () => (state.open = false));
</script>

<template>
    <div class="relative flex flex-col items-end gap-2" ref="target">
        <Transition name="fade">
            <div
                class="fixed w-full h-full inset-0 bg-black/70 z-40"
                @click="state.open = false"
                v-if="state.open"
            ></div>
        </Transition>
        <Transition>
            <div
                class="flex flex-col items-end gap-2 mr-1 z-50"
                style="transform-origin: bottom right"
                v-if="state.open"
            >
                <button
                    class="flex items-center gap-2 bg-white shadow text-[#EE0C15] px-6 py-2.5 text-xs rounded-full font-semibold"
                    @click="$router.push('/permit')"
                >
                    <Icon
                        icon="solar:calendar-add-bold-duotone"
                        width="1.5rem"
                        height="1.5rem"
                    />
                    <span>Buat Pengajuan Izin</span>
                </button>
                <button
                    class="flex items-center gap-2 bg-white shadow text-[#EE0C15] px-6 py-2.5 text-xs rounded-full font-semibold"
                    @click="$router.push('/manual')"
                >
                    <Icon
                        icon="solar:alarm-add-bold-duotone"
                        width="1.5rem"
                        height="1.5rem"
                    />
                    <span>Absensi Manual</span>
                </button>
            </div>
        </Transition>
        <button
            class="rounded-full text-[#EE0C15] flex items-center justify-center shadow-lg overflow-hidden bg-white z-50"
            @click="state.open = !state.open"
        >
            <Icon
                icon="solar:add-circle-bold"
                width="3rem"
                height="3rem"
                class="transform transition-all duration-300 scale-115"
                :class="state.open ? 'rotate-45' : 'rotate-0'"
            />
        </button>
    </div>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: all 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: scale(0) translateY(200px);
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
