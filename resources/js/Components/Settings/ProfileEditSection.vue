<script setup>
import {faPencil, faXmark} from "@fortawesome/free-solid-svg-icons"
import Button from '@/Components/Generic/Button.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Card from "@/Components/Generic/Card.vue"
import ProfileForm from "@/Components/Forms/ProfileForm.vue"
import {inject, ref} from "vue";

const editing = ref(false)
const { addFlashMessage } = inject('flashMessages')

const profileUpdated = () => {
    addFlashMessage({
        content: 'Profile updated successfully.',
    })
    editing.value = false
}
</script>

<template>
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-medium">Profile</h2>

            <Button v-if="editing" color="base" variant="light" :on-click="() => editing = false">
                <FontAwesomeIcon :icon="faXmark" /> Cancel
            </Button>
            <Button v-else :on-click="() => editing = true">
                <FontAwesomeIcon :icon="faPencil" /> Edit
            </Button>
        </div>

        <Card v-if="editing">
            <ProfileForm
                with-name-field
                with-date-of-birth-field
                :on-success="profileUpdated"
                next-route="settings.profile"
                :existing-profile="$page.props.profile" />
        </Card>
        <ProfileCard v-else :profile="$page.props.profile" />
    </section>
</template>
