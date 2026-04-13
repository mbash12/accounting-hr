<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";

const props = defineProps({
    label: {
        type: String,
    },
    placeholder: {
        type: String,
    },
    required: {
        type: Boolean,
    },
});
const model = defineModel();
</script>

<template>
    <label class="flex flex-col gap-1">
        <span class="text-gray-700 text-sm font-medium"
            >{{ props.label
            }}<span class="text-red-500" v-if="props.required">*</span></span
        >
        <VueDatePicker
            v-model="model"
            class="text-sm"
            :placeholder="props.placeholder"
            :required="props.required"
            :enable-time-picker="false"
        ></VueDatePicker>
    </label>
</template>

<style>
.dp__pointer.dp__input_readonly.dp__input.dp__input_icon_pad.dp__input_reg {
    font-size: 0.875rem;
    line-height: 1.25rem;
    padding: 0.5rem 0.5rem;
}
.dp__input_wrap div {
    display: none;
}
</style>
