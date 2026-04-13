<script setup>
import { reactive } from "vue";
import Button from "./Button.vue";
const props = defineProps({
    label: {
        type: String,
    },
    options: {
        type: Array,
    },
    placeholder: {
        type: String,
    },
    modelValue: {
        type: Object,
    },
    required: {
        type: Boolean,
    },
    disabled: {
        type: Boolean,
    }
});
const state = reactive({
    open: false,
});
const emit = defineEmits(["update:modelValue"]);
const handleSelect = (e) => {
    emit("update:modelValue", e);
    setTimeout(() => {
        state.open = false;
    }, 100);
};
</script>
<template>
    <label class="flex flex-col gap-1 relative">
        <span class="text-gray-700 text-sm font-medium"
            >{{ props.label
            }}<span class="text-red-500" v-if="props.required">*</span></span
        >
        <button
            type="button"
            class="p-2 h-38px border border-gray-300 rounded-lg text-sm"
            :class="`${props.disabled ? 'bg-gray-50 cursor-not-allowed' : ''}`"
            @click="state.open = true"
        >
            <span
                class="w-full h-full text-sm block text-left text-gray-400"
                v-if="!props.modelValue"
                >{{ props.placeholder }}</span
            >
            <span
                class="w-full h-full text-sm block text-left text-black"
                v-else
                >{{ props.modelValue.text }}</span
            >
        </button>
        <input type="text" class="h-1px focus:outline-none w-full absolute -bottom-1px text-transparent bg-transparent" v-model="props.modelValue" :required="props.required">
        <Transition name="fade">
            <div
                class="fixed inset-0 p-4 bg-black bg-opacity-50 z-10"
                v-if="state.open && !props.disabled"
            >
                <div
                    class="flex flex-col w-full h-full bg-white rounded-xl relative overflow-hidden"
                >
                    <div class="shadow relative z-1">
                        <span
                            class="text-sm font-bold bg-white flex px-4 py-4 sticky top-0"
                            >{{ props.label }}</span
                        >
                    </div>
                    <div class="flex flex-col overflow-auto flex-1">
                        <div v-for="group in props.options" class="">
                            <span
                                class="text-sm font-bold bg-gray-100 flex px-4 py-2 sticky top-0"
                                >{{ group.group }}</span
                            >
                            <div class="flex flex-col">
                                <div
                                    v-for="item in group.list"
                                    class="flex items-center gap-2 py-3 px-4"
                                    @click="handleSelect(item)"
                                >
                                    <span class="text-sm">
                                        {{ item.text }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full gap-4 bg-white px-4 py-2 border-t flex">
                        <Button
                            block
                            variant="gray"
                            class="text-14px"
                            @click="state.open = false"
                            >Kembali</Button
                        >
                    </div>
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
