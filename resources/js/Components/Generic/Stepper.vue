<script setup>
import { computed } from 'vue'
import { faCheck } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const props = defineProps({
    steps: Array,
    selectedStepValue: String,
    color: {
        type: String,
        default: 'primary',
    },
})

const selectedStepIdx = computed(() => {
    return props.steps.findIndex(step => step.value === props.selectedStepValue)
})

const stepsWithStatus = computed(() => {
    return props.steps.map((step, idx) => {
        if (idx < selectedStepIdx.value) {
            return { ...step, status: 'completed' }
        } else if (idx === selectedStepIdx.value) {
            return { ...step, status: 'in-progress' }
        }
        return { ...step, status: 'uncompleted' }
    })
})

const getStepStyles = (step) => {
    if (step.status === 'uncompleted') {
        return 'text-secondary-text bg-card-accent'
    }
    return `text-${props.color} bg-${props.color}/30`
}
</script>

<template>
    <div class="w-full">
        <div class="flex justify-between items-center gap-2 px-4">
            <div
                v-for="step in stepsWithStatus"
                :key="step.value"
                :class="['w-10 h-10 rounded-full flex items-center justify-center', getStepStyles(step)]">
                <font-awesome-icon :icon="step.status === 'completed' ? faCheck : step.icon" />
            </div>
        </div>
        <div class="flex justify-between items-center mt-1 px-3">
            <p
                v-for="step in stepsWithStatus"
                :key="'label-' + step.value"
                :class="[step.status === 'uncompleted' ? 'text-secondary-text' : `text-${color}`, 'text-xs font-medium text-center']"
            >
                {{ step.label }}
            </p>
        </div>
    </div>
</template>
