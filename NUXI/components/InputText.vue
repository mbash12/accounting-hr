<script setup>
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
  required: {
    type: Boolean,
  }
})
const emit = defineEmits(['update:modelValue'])
const onChange = (e) => {
  emit('update:modelValue', e.target.value)
}
</script>
<template>
  <label class="flex flex-col gap-1">
    <span class="text-gray-700 text-sm font-medium">{{ props.label }}<span class="text-red-500" v-if="props.required">*</span></span>
    <input type="text" :placeholder="props.placeholder" :value="props.modelValue" @input="onChange" class="p-2 border border-gray-300 rounded-lg text-sm" :required="props.required"/>
  </label>
</template>