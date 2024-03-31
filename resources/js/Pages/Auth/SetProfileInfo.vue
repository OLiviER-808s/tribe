<script setup lang="ts">
import EntryLayout from '@/Layouts/EntryLayout.vue'
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm } from '@inertiajs/vue3'
import { DEFAULT_PROFILE_PIC, REGISTER_STEPS } from '@/lib/constants'
import Textbox from '@/Components/Generic/Textbox.vue'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import { ref } from 'vue'

const cropping = ref(false)
const src = ref(DEFAULT_PROFILE_PIC)

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
                <div class="mb-4">
                    <AvatarInput v-model:cropping="cropping" v-model:src="src" />
                </div>

                <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
                    <Textbox 
                    v-model="form.username"
                    label="Username"
                    placeholder="Your username..."
                    />

                    <Textarea
                    v-model="form.bio"
                    label="Write your bio..."
                    placeholder="Bio"
                    />

                    <Button type="submit">Continue</Button>
                </form>
            </div>
        </section>
    </EntryLayout>
</template>
