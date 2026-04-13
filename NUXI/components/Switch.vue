<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
  }
})
const emit = defineEmits(['update:modelValue'])
</script>
<template>
  <div class="w-full h-full flex" @click="emit('update:modelValue', !props.modelValue)">
    <div
      class="w-36px h-20px rounded-full flex items-center p-2px transition-all duration-300"
      :class="`${props.modelValue ? 'bg-green-500' : 'bg-gray-300'}`"
    >
      <div
        class="w-16px h-16px rounded-full bg-white transform transition-all duration-300" 
        :class="`${props.modelValue ? 'translate-x-16px' : 'translate-x-0'}`"
      ></div>
    </div>
  </div>
</template>