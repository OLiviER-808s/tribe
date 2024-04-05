<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import { ModalsContainer, useModal } from 'vue-final-modal'
import Button from '@/Components/Generic/Button.vue'
import ProfileEditModal from '@/Components/Modals/ProfileEditModal.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil } from '@fortawesome/free-solid-svg-icons'

const { open, close } = useModal({
    component: ProfileEditModal,
    attrs: {
        onClose() {
            close()
        }
    }
})
</script>

<template>
    <SettingsLayout>
        <section class="flex justify-center pt-8">
            <ModalsContainer />

            <div class="w-full max-w-2xl p-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-medium">Profile</h2>

                    <Button :on-click="open">
                        <FontAwesomeIcon :icon="faPencil" /> Edit
                    </Button>
                </div>

                <ProfileCard :profile="$page.props.profile" />
            </div>
        </section>
    </SettingsLayout>
</template>