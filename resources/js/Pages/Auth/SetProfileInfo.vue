<script setup lang="ts">
import EntryLayout from '@/Layouts/EntryLayout.vue'
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm } from '@inertiajs/vue3'
import { REGISTER_STEPS } from '@/lib/constants';

const form = useForm({
    username: '',
    bio: '',
    photo: null
})

const submit = () => {
    form.post(route('login'))
}
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="account" />
        </div>

        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <form class="flex flex-col gap-4" @submit.prevent="submit">
                    <AvatarInput />
                </form>
            </div>
        </section>
    </EntryLayout>
</template>
