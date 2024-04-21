<script setup>
import { useDates } from '@/Composables/useDates'
import { computed } from 'vue';

const { action } = defineProps({
    action: Object
})

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(action.sent_at)))
</script>

<template>
    <div class="flex items-center justify-center gap-6 py-2 text-sm text-secondary-text">
        <p class="font-medium">{{ action.text }}</p>
        <p>{{ timestamp }}</p>
    </div>
</template>