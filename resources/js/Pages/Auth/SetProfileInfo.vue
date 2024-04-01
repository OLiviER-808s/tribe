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

const cropping = ref(false)
const src = ref(DEFAULT_PROFILE_PIC)

const errors = ref({})

const form = useForm({
    _method: 'PATCH',
    name: usePage().props.auth.user.name,
    username: '',
    bio: '',
    photo: null,
    next_route: 'profile.interests'
})

const submit = () => {
    form.post(route('profile.update'), {
        onError: (errs) => {
            errors.value = errs
        }
    })
}
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="profile" />
        </div>

        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <div class="mb-4">
                    <AvatarInput v-model:cropping="cropping" v-model:src="src" v-model:file="form.photo" />
                </div>

                <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
                    <UsernameInput v-model:username="form.username" :error="errors.username" />

                    <Textarea
                    v-model="form.bio"
                    label="Bio"
                    placeholder="Write your bio..."
                    :error="errors.bio"
                    />

                    <Button type="submit">Continue</Button>
                </form>
            </div>
        </section>
    </EntryLayout>
</template>
