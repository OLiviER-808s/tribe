<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import Button from '@/Components/Generic/Button.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import Tag from '@/Components/Generic/Tag.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {faPencil, faXmark} from '@fortawesome/free-solid-svg-icons'
import {ref} from "vue";
import Card from "@/Components/Generic/Card.vue";
import ProfileForm from "@/Components/Forms/ProfileForm.vue";
import InterestForm from "@/Components/Forms/InterestForm.vue";

const props = defineProps({
    categories: Array
})

const editingProfile = ref(false)
const editingInterests = ref(false)
</script>

<template>
    <SettingsLayout>
        <section class="flex justify-center pt-8">
            <div class="w-full max-w-2xl p-2 flex flex-col gap-12">
                <!-- Profile -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-medium">Profile</h2>

                        <Button v-if="editingProfile" color="base" variant="light" :on-click="() => editingProfile = false">
                            <FontAwesomeIcon :icon="faXmark" /> Cancel
                        </Button>
                        <Button v-else :on-click="() => editingProfile = true">
                            <FontAwesomeIcon :icon="faPencil" /> Edit
                        </Button>
                    </div>

                    <Card v-if="editingProfile">
                        <ProfileForm
                            with-name-field
                            with-date-of-birth-field
                            :on-success="() => editingProfile = false"
                            next-route="settings.profile"
                            :existing-profile="$page.props.profile" />
                    </Card>
                    <ProfileCard v-else :profile="$page.props.profile" />
                </div>

                <!-- Interests -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-medium">Interests</h2>

                        <Button v-if="editingInterests" color="base" variant="light" :on-click="() => editingInterests = false">
                            <FontAwesomeIcon :icon="faXmark" /> Cancel
                        </Button>
                        <Button v-else :on-click="() => editingInterests = true">
                            <FontAwesomeIcon :icon="faPencil" /> Edit
                        </Button>
                    </div>

                    <Card v-if="editingInterests" styles="w-full flex flex-col max-h-[1200px]">
                        <InterestForm
                            next-route="settings.profile"
                            :on-success="() => editingInterests = false"
                            :default-categories="categories"
                            :default-selected-interests="$page.props.profile.interests.map(topic => topic.uuid)" />
                    </Card>
                    <div v-else class="flex gap-2 items-center flex-wrap">
                        <div v-for="interest in $page.props.profile.interests" :key="interest.uuid">
                            <Tag>{{ interest.label }}</Tag>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </SettingsLayout>
</template>
