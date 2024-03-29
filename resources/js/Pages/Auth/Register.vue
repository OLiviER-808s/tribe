<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import Button from '@/Components/Generic/Button.vue'
import { faIcons, faUser, faUserAstronaut } from '@fortawesome/free-solid-svg-icons';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        }
    })
}

const steps = [
    { label: 'Account', value: 'account', icon: faUser },
    { label: 'Profile', value: 'profile', icon: faUserAstronaut },
    { label: 'Interests', value: 'interests', icon: faIcons }
]
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="steps" selected-step-value="account" />
        </div>

        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <form class="flex flex-col gap-4" @submit.prevent="submit">
                    <Textbox 
                    v-model="form.name"
                    name="name"
		            label="Full Name"
		            placeholder="Your name..."
                    />

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
		            placeholder="Your password..."
                    />

                    <Button type="submit">Continue</Button>
                </form>
            </div>
        </section>
    </EntryLayout>
</template>
