<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Textbox from '../../Components/Generic/Textbox.vue'
import GoogleOAuthButton from '../../Components/Auth/GoogleOAuthButton.vue'
import Button from '../../Components/Generic/Button.vue'

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <EntryLayout>
        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <div class="my-6">
                    <GoogleOAuthButton />
                </div>

                <h3 class="mb-6 text-center font-medium text-secondary-text">OR</h3>

                <form class="flex flex-col gap-4" @submit.prevent="submit">
                    <Textbox 
                    v-model="form.email"
                    name="email"
		            label="Email"
		            placeholder="Your email..."
                    />

                    <Textbox 
                    v-model="form.password"
                    name="password"
		            label="Password"
                    type="password"
		            placeholder="Your password..."
                    />

                    <Button type="submit">Continue</Button>
                </form>
            </div>
        </section>

        <p class="text-center text-sm p-5">
            Don't have an account? <a :href="route('register')" class="text-secondary font-medium">Signup</a>
        </p>
    </EntryLayout>
</template>
