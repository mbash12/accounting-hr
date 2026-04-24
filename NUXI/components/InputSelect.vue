<script setup>
import { onClickOutside } from "@vueuse/core";

import { computed, reactive, ref } from "vue";
const props = defineProps({
    label: {
        type: String,
    },
    placeholder: {
        type: String,
    },
    modelValue: {
        type: String,
    },
    options: {
        type: Array,
    },
    required: {
        type: Boolean,
    },
});
const target = ref(null);

const emit = defineEmits(["update:modelValue"]);
const onChange = (value) => {
    emit("update:modelValue", value);
};
const valueText = computed(() => {
    const option = props.options.find((o) => o.value === props.modelValue);
    if (option) {
        return option.text;
    }
});
const state = reactive({
    open: false,
});
onClickOutside(target, () => (state.open = false));
</script>
<template>
    <label class="flex flex-col gap-1 relative" ref="target">
        <span class="text-gray-700 text-sm font-medium"
            >{{ props.label
            }}<span class="text-red-500" v-if="props.required">*</span></span
        >
        <button
            type="button"
            class="p-2 border border-gray-300 rounded-lg text-sm text-left flex items-center"
            @click="state.open = !state.open"
        >
            <span class="text-black flex-1" v-if="valueText">
                {{ valueText }}
            </span>
            <span class="text-gray-400 flex-1" v-else>
                {{ props.placeholder }}
            </span>
            <svg
                width="15"
                height="8"
                viewBox="0 0 15 8"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M1.51563 1.03125L7.51563 7.03125L13.5156 1.03125"
                    stroke="#40B6F4"
                    stroke-width="1.70667"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </button>
        <Transition name="fade">
            <div
                class="w-full absolute top-66px bg-white rounded-xl shadow-lg border flex flex-col py-3"
                v-if="state.open"
            >
                <div
                    class="p-4 text-14px"
                    :class="option.value === props.modelValue ? 'text-[#40B6F4] bg-[#E0F3FE] font-bold' : ' font-medium'"
                    v-for="option in props.options"
                    :key="option"
                    @click="onChange(option.value)"
                >
                    {{ option.text }}
                </div>
            </div>
        </Transition>
    </label>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from {
    opacity: 0;
}
.fade-leave-to {
    opacity: 0;
}
</style>
