<script setup>
const props = defineProps({
    type: {
        type: String,
    },
    variant: {
        type: String,
        default: "red",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    block: {
        type: Boolean,
        default: false,
    }
});
const variants = {
    red: "text-white bg-gradient-to-bl from-[#60a5fa] to-[#40B6F4]",
    white: "bg-white text-[#40B6F4] shadow-md border border-[#BFDBFE]",
    gray: "bg-[#F2F2F2] text-black",
    green: "bg-gradient-to-bl from-[#00E717] to-[#00C714] text-white",
};
</script>
<template>
    <button
        :type="props.type ?? 'button'"
        :class="`transition-all duration-200 font-semibold text-17px rounded-full h-50px px-4 w-fit-content  ${variants[props.variant]} ${
            props.disabled ? 'cursor-not-allowed opacity-50' : ''
        } ${props.block ? 'w-full' : ''} `"
        :disabled="props.disabled"
    >
        <slot />
    </button>
</template>
