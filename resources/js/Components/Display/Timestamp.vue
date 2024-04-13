<script setup>
import { useDates } from '@/Composables/useDates';
import Card from '../Generic/Card.vue'
import { computed } from 'vue';

const props = defineProps({
    timestamp: Date,
    show: {
        type: Boolean,
        default: true
    },
})

const { formatDate } = useDates()
const formatedTimestamp = computed(() => formatDate(new Date(props.timestamp)))
</script>

<template>
    <div v-if="show" class="py-2 flex justify-center">
        <Card styles="w-auto py-1 px-4">
            <p class="text-sm">{{ formatedTimestamp }}</p>
        </Card>
    </div>
</template>