<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import { ModalsContainer, useModal } from 'vue-final-modal'
import Button from '@/Components/Generic/Button.vue'
import ProfileEditModal from '@/Components/Modals/ProfileEditModal.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import Tag from '@/Components/Generic/Tag.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil, faRightFromBracket } from '@fortawesome/free-solid-svg-icons'
import InterestsEditModal from '@/Components/Modals/InterestsEditModal.vue'

const props = defineProps({
    categories: Array
})

const profileModal = useModal({
    component: ProfileEditModal,
    attrs: {
        onClose() {
            profileModal.close()
        }
    }
})

const interestsModal = useModal({
    component: InterestsEditModal,
    attrs: {
        categories: props.categories,
        onClose() {
            interestsModal.close()
        }
    }
})
</script>

<template>
    <SettingsLayout>
        <section class="flex justify-center pt-8">
            <ModalsContainer />

            <div class="w-full max-w-2xl p-2 flex flex-col gap-12">
                <!-- Profile -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-medium">Profile</h2>

                        <Button :on-click="profileModal.open">
                            <FontAwesomeIcon :icon="faPencil" /> Edit
                        </Button>
                    </div>

                    <ProfileCard :profile="$page.props.profile" />
                </div>

                <!-- Interests -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-medium">Interests</h2>

                        <Button :on-click="interestsModal.open">
                            <FontAwesomeIcon :icon="faPencil" /> Edit
                        </Button>
                    </div>

                    <div class="flex gap-2 items-center flex-wrap">
                        <div v-for="interest in $page.props.profile.interests" :key="interest.uuid">
                            <Tag>{{ interest.label }}</Tag>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </SettingsLayout>
</template>
