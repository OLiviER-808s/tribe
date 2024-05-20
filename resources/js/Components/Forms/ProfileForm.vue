<script setup>
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { DEFAULT_PROFILE_PIC } from '@/lib/constants'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import UsernameInput from '@/Components/Auth/UsernameInput.vue'
import { ref } from 'vue'
import CityDropdown from '../Dropdowns/CityDropdown.vue'

const props = defineProps({
    nextRoute: String,
    existingProfile: {
        type: Object,
        default: null
    },
    submitButtonText: {
        type: String,
        default: 'Confirm'
    },
    onSuccess: {
        type: Function,
        default: () => {}
    }
})

const page = usePage()

const cropping = ref(false)
const src = ref(props.existingProfile?.photo ?? DEFAULT_PROFILE_PIC)

const form = useForm({
    _method: 'PATCH',
    name: page.props.auth.user.name,
    username: props.existingProfile?.username,
    bio: props.existingProfile?.bio,
    location: props.existingProfile?.location,
    photo: null,
    next_route: props.nextRoute
})

const submit = () => {
    form.post(route('profile.update'), {
        onSuccess: () => {
            props.onSuccess()
        }
    })
}
</script>

<template>
    <div>
        <div class="mb-4">
            <AvatarInput v-model:cropping="cropping" v-model:src="src" v-model:file="form.photo" />
        </div>

        <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
            <UsernameInput v-model="form.username" :error="form.errors.username" :existing-username="existingProfile?.username" />

            <Textarea
            v-model="form.bio"
            label="Bio"
            placeholder="Write your bio..."
            :error="form.errors.bio"
            />

            <CityDropdown v-model="form.location" :default-search-value="form.location" />

            <Button type="submit">{{ submitButtonText }}</Button>
        </form>
    </div>
</template>
