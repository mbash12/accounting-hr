<script setup>
const model = defineModel()
const props = defineProps({
  options: {
    type: Array,
  },
  modelValue: {
    type: String,
  },
  disabled: {
    type: Boolean,
  }
})
const emit = defineEmits(['update:modelValue'])
const onSelect = (e) => {
  if(props.disabled) return
  emit('update:modelValue', e.value)
}
</script>
<template>
  <div class="flex bg-gray-50 p-1.5 rounded-full relative" :class="props.disabled ? 'cursor-not-allowed bg-gray-100' : ''">
    <div class="relative flex items-center w-full ">
      <div class="h-38px rounded-full absolute transition-all duration-300 bg-gradient-to-bl from-[#FF6C69] to-[#F20A13] border border-[#FFB7BA]"
           :style="`width:${100 / props.options.length}%; left:${(100 / props.options.length) * (props.options.findIndex(i => i.value === modelValue))}%`"
           
      >
      </div>
      <button type="button" v-for="o in props.options" :key="o.value" class="relative flex-1 flex items-center justify-center text-xs font-semibold py-2 transition-all duration-300" :class="o.value === modelValue ? 'text-white' : 'text-gray-500'" @click="onSelect(o)">
        {{ o.text }}
      </button>
    </div>
  </div>
</template>