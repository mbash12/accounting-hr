<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';

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
})
const model = defineModel()

</script>
<template>
  <label class="flex flex-col gap-1">
    <span class="text-gray-700 text-sm font-medium">{{ props.label }}<span class="text-red-500" v-if="props.required">*</span></span>
    <textarea :placeholder="props.placeholder" v-model="model" class="p-2 border h-30 border-gray-300 rounded-lg text-sm" :required="props.required"></textarea>
  </label>
</template>
