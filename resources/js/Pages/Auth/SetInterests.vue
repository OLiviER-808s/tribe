<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import InterestForm from '@/Components/Forms/InterestForm.vue'
import { REGISTER_STEPS } from '@/lib/constants'
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    categories: Array
})
</script>

<template>
    <EntryLayout title="Select Your Interests">
        <Head title="Register" />

        <div class="mt-4 mb-4">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="interests" />
        </div>

        <InterestForm :default-categories="categories" next-route="discover" />
    </EntryLayout>
</template>
