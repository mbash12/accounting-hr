<script setup>
import { Icon } from "@iconify/vue";
const props = defineProps({
  modelValue: {
    type: Boolean,
  }
})
const emit = defineEmits(['toggle'])
</script>
<template>
  <div class="h-1.5rem transition-all duration-300" @click="emit('toggle')" :class="`${props.modelValue ? 'opacity-100' : 'opacity-40'}`">
    <Icon icon="ph:bell-slash-fill" width="1.5rem" height="1.5rem"  v-show="!props.modelValue"/>
    <Icon icon="ph:bell-fill" width="1.5rem" height="1.5rem" v-show="props.modelValue" />
  </div>
</template>