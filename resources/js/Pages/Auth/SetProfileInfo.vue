<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import ProfileForm from '@/Components/Forms/ProfileForm.vue'
import { useIsHandheld } from '@/Composables/useIsHandheld'
import Card from '@/Components/Generic/Card.vue'
import { REGISTER_STEPS } from '@/lib/constants'
import {Head} from "@inertiajs/vue3";

const { isHandheld } = useIsHandheld()
</script>

<template>
    <EntryLayout title="Create Your Profile">
        <Head title="Register" />

        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="profile" />
        </div>

        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <Card v-if="isHandheld">
                    <ProfileForm next-route="profile.interests" submit-button-text="Continue" />
                </Card>
                <ProfileForm v-else next-route="profile.interests" submit-button-text="Continue" />
            </div>
        </section>
    </EntryLayout>
</template>
