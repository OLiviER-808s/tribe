<script setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ref } from 'vue'
import Textbox from './Textbox.vue'
import { faCalendar } from '@fortawesome/free-solid-svg-icons'
import { useDates } from '@/Composables/useDates'
import { watch } from 'vue'

const today = new Date()

const props = defineProps({
    modelValue: Date,
    error: String,
    label: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: ''
    },
    defaultDate: Date
})
const emit = defineEmits(['update:modelValue'])

const { formatDate } = useDates()

const dp = ref(null)
const date = ref(props.defaultDate)

watch(date, () => emit('update:modelValue', date.value))
</script>

<template>
    <div>
        <label class="font-medium">{{ label }}</label>

        <VueDatePicker v-model="date" ref="dp" :format="formatDate" auto-apply :enable-time-picker="false" :max-date="today">
            <template #dp-input="{ value }">
                <Textbox
                    :placeholder="placeholder"
                    :value="value"
                    :icon="faCalendar"
                    :error="!!error"
                />
            </template>
        </VueDatePicker>

        <p v-if="error" class="text-error text-sm">{{ error }}</p>
    </div>
</template>

