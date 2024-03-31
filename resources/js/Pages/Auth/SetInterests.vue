<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { DEFAULT_PROFILE_PIC, REGISTER_STEPS } from '@/lib/constants'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import UsernameInput from '@/Components/Auth/UsernameInput.vue'
import { ref } from 'vue'

const { categories } = defineProps({
    categories: Array
})

const form = useForm({
    
})

const submit = () => {
    
}
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="interests" />
        </div>

        
    </EntryLayout>
</template>