<script setup>
import { computed } from "vue";

const props = defineProps({
  label: {
    type: String,
  },
  placeholder: {
    type: String,
  },
  modelValue: {
    type: Number,
  },
  required: {
    type: Boolean,
  },
  disabled: {
    type: Boolean,
  }
});

const emit = defineEmits(["update:modelValue"]);

const onChange = (e) => {
  let value = e.target.value.replace(/\D/g, "");
  emit("update:modelValue", parseFloat(value) || 0);
};

const formattedModelValue = computed(() => {
  const value = props.modelValue ? new Intl.NumberFormat("id-ID").format(props.modelValue) : "";
  return value;
});
</script>
<template>
  <label class="flex flex-col gap-1">
    <span class="text-gray-700 text-sm font-medium">{{ props.label }}<span class="text-red-500" v-if="props.required">*</span></span>
    <input
      type="text"
      :placeholder="props.placeholder"
      :value="formattedModelValue"
      @input="onChange"
      class="p-2 border border-gray-300 rounded-lg text-sm"
      :required="props.required"
      :disabled="props.disabled"
    />
  </label>
</template>