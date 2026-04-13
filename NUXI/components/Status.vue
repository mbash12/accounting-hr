<script setup>
const props = defineProps({
  status: {
    type: String,
  }
})
const colors = {
  submitted: "text-[#0086B0] bg-[#D9F8FF]",
  approved: "text-[#009317] bg-[#CDFFCC]",
  rejected: "text-[#F41D23] bg-[#FFEBEB]",
  cancelled: "text-gray-500 bg-gray-100",
  updating: "text-black bg-gray-100",
}
const text = {
  submitted: "Diajukan",
  approved: "Disetujui",
  rejected: "Ditolak",
  cancelled: "Dibatalkan",
  updating: "Mengupdate",
}
</script>
<template>
  <div :class="colors[props.status] + ' text-xs px-2 py-0.5 rounded-full font-semibold capitalize'">
    {{ text[props.status] }}
  </div>
</template>