<script setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ref } from 'vue'
import Textbox from './Textbox.vue';
import { faCalendar } from '@fortawesome/free-solid-svg-icons';
import { useDates } from '@/Composables/useDates';

const dp = ref(null)
const date = ref(null)

const { formatDate } = useDates()
</script>

<template>
    <VueDatePicker v-model="date" ref="dp" :format="formatDate">
        <template #dp-input="{ value, onInput, onEnter, onTab, onClear, onBlur, onKeypress, onPaste, isMenuOpen }">
            <Textbox
            placeholder="Date of Birth"
            :value="value"
            :icon="faCalendar" 
            />
        </template>
    </VueDatePicker>
</template>

