<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import InterestForm from '@/Components/Forms/InterestForm.vue'
import { REGISTER_STEPS } from '@/lib/constants'

const props = defineProps({
    categories: Array
})
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-4">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="interests" />
        </div>

        <InterestForm :default-categories="categories" next-route="discover" />
    </EntryLayout>
</template>