<script setup>
const props = defineProps({
    avatars: Array,
    width: {
        type: String,
        default: 'w-6'
    },
    overlap: {
        type: Number,
        default: 12
    }
})
</script>

<template>
    <div class="flex items-center">
        <img
            v-for="(avatar, idx) in avatars"
            :key="idx"
            :src="avatar"
            :alt="`avatar ${idx + 1}`"
            :class="`${width} rounded-full relative`"
            :style="{ marginLeft: idx === 0 ? '0' : `-${overlap}px` }"
        />
    </div>
</template>