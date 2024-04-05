<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import UsernameInput from '@/Components/Auth/UsernameInput.vue'
import Textbox from '../Generic/Textbox.vue'
import { ref } from 'vue'

const emit = defineEmits(['close'])

const profile = usePage().props.profile

const cropping = ref(false)
const src = ref(profile.photo)

const errors = ref({})

const form = useForm({
    _method: 'PATCH',
    name: profile.name,
    username: profile.username,
    bio: profile.bio,
    photo: null,
    next_route: 'settings.account'
})

const submit = () => {
    form.post(route('profile.update'), {
        onError: (errs) => {
            errors.value = errs
        },
        onSuccess: () => {
            emit('close')
        }
    })
}
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl">
        <Card styles="w-full">
            <div class="mb-4">
                <AvatarInput v-model:cropping="cropping" v-model:src="src" v-model:file="form.photo" />
            </div>

            <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
                <Textbox 
                v-model="form.name"
                name="name"
                label="Full Name*"
                placeholder="Your name..."
                :error="errors.name"
                />

                <UsernameInput v-model:username="form.username" :error="errors.username" :existing-username="profile.username" />

                <Textarea
                v-model="form.bio"
                label="Bio"
                placeholder="Write your bio..."
                :error="errors.bio"
                />

                <Button type="submit">Continue</Button>
            </form>
        </Card>
    </VueFinalModal>
</template>