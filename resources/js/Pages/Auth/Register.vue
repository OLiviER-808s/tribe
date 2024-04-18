<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { REGISTER_STEPS } from '@/lib/constants'
import RegisterForm from '@/Components/Forms/RegisterForm.vue'
import Card from '@/Components/Generic/Card.vue';
import { useIsHandheld } from '@/Composables/useIsHandheld';

const { isHandheld } = useIsHandheld()
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="account" />
        </div>

        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <Card v-if="isHandheld">
                    <RegisterForm />
                </Card>
                <RegisterForm v-else />
            </div>
        </section>

        <p class="text-center text-sm p-5">
            Already have an account? <a :href="route('login')" class="text-secondary font-medium">Login</a>
        </p>
    </EntryLayout>
</template>
