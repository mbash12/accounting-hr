<script setup>
import {ref} from 'vue';
import {ASSETURL} from '@/deps/env.js';
import { Icon } from '@iconify/vue';
import {uploadImage, loading} from '@/deps/service.js'
const uploader = ref(null)
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
const handleUpload = (e) => {
  loading()
  const file = e.target.files[0]
  uploadImage(file).then((res) => {
    if(res.path){
      model.value = res.path
    }
  }).finally(()=>{
    loading(false)
  })

}
const clearModel = () => {
  setTimeout(() => {
    model.value = null
  }, 100);
}
</script>
<template>
  <label class="flex flex-col gap-1">
    <span class="text-gray-700 text-sm font-medium">{{ props.label }}<span class="text-red-500" v-if="props.required">*</span></span>
    <div class="border border-gray-300 rounded relative h-40">
      <div class="flex flex-col items-center justify-center w-full h-full gap-1">
        <Icon icon="solar:cloud-upload-bold-duotone" width="3rem" height="3rem" class="text-blue-300" />
        <span class="text-xs text-blue-gray-500">Unggah Gambar</span>
      </div>
      <input type="file" accept="image/*" class="absolute w-full h-full inset-0 opacity-0" :required="props.required" @change="handleUpload" v-if="!model"/>
      <div  class="w-full h-full absolute inset-0 block" v-if="model">
        <a :href="`${ASSETURL+model}`" target="_blank"  class="w-full h-full bg-white block" >
          <img :src="ASSETURL+model" alt="" class="w-full h-full object-cover">
        </a>
        <Icon icon="solar:close-circle-bold" width="1.5rem" height="1.5rem" class="text-red-500 absolute top-1 right-1" @click="clearModel"/>
      </div>
    </div>
  </label>
</template>
