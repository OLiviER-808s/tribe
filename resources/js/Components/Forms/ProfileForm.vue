<script setup>
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { DEFAULT_PROFILE_PIC } from '@/lib/constants'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import UsernameInput from '@/Components/Auth/UsernameInput.vue'
import { ref } from 'vue'
import CityDropdown from '../Dropdowns/CityDropdown.vue'
import Textbox from "@/Components/Generic/Textbox.vue";
import DatePicker from "@/Components/Generic/DatePicker.vue";

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
    withDateOfBirthField: Boolean,
    withNameField: Boolean
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
    dob: new Date(page.props.auth.user.date_of_birth),
    photo: null,
    next_route: props.nextRoute
})

const submit = () => form.post(route('profile.update'))
</script>

<template>
    <div>
        <div class="mb-4">
            <AvatarInput v-model:cropping="cropping" v-model:src="src" v-model:file="form.photo" />
        </div>

        <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
            <Textbox
                v-if="withNameField"
                v-model="form.name"
                label="Full Name"
                placeholder="Your full name..."
                :error="form.errors.name"
            />

            <UsernameInput v-model="form.username" :error="form.errors.username" :existing-username="existingProfile?.username" />

            <Textarea
            v-model="form.bio"
            label="Bio"
            placeholder="Write your bio..."
            :maxlength="250"
            :error="form.errors.bio"
            />

            <CityDropdown v-model="form.location" :default-search-value="form.location" />

            <DatePicker
                v-if="withDateOfBirthField"
                label="Date of Birth"
                placeholder="Your date of birth..."
                v-model="form.dob"
                :default-date="form.dob"
                :error="form.errors.dob" />

            <Button type="submit">{{ submitButtonText }}</Button>
        </form>
    </div>
</template>
