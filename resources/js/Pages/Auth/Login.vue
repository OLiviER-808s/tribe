<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import GoogleOAuthButton from '../../Components/Auth/GoogleOAuthButton.vue'
import LoginForm from '@/Components/Forms/LoginForm.vue'
import { useIsHandheld } from '@/Composables/useIsHandheld';
import Card from '@/Components/Generic/Card.vue';

const { isHandheld } = useIsHandheld()
</script>

<template>
    <EntryLayout>
        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <div class="my-6">
                    <GoogleOAuthButton />
                </div>

                <h3 class="mb-6 text-center font-medium text-secondary-text">OR</h3>

                <Card v-if="isHandheld">
                    <LoginForm />
                </Card>
                <LoginForm v-else />
            </div>
        </section>

        <p class="text-center text-sm p-5">
            Don't have an account? <a :href="route('register')" class="text-secondary font-medium">Signup</a>
        </p>
    </EntryLayout>
</template>
