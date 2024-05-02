<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import Button from '@/Components/Generic/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faRightFromBracket } from '@fortawesome/free-solid-svg-icons'
import { router } from '@inertiajs/vue3'
import ChangePassword from '@/Components/Settings/ChangePassword.vue'

const logout = () => router.post(route('logout'))
</script>

<template>
    <SettingsLayout>
        <template #title>
            <h1>Account Settings</h1>
        </template>

        <section class="flex justify-center pt-8">
            <div class="w-full max-w-2xl p-2 flex flex-col gap-12">
                <!-- Logout -->
                <div class="flex items-center justify-between">
                    <p>Logged in as <span class="font-medium">{{ $page.props.auth.user.email }}</span></p>

                    <Button :on-click="logout" color="secondary" variant="light" styles="text-sm">
                        <FontAwesomeIcon :icon="faRightFromBracket" /> Logout
                    </Button>
                </div>

                <!-- Change Password -->
                <div v-if="!$page.props.auth.isOAuthUser">
                    <h2 class="text-2xl font-medium mb-4">Update Password</h2>

                    <ChangePassword />
                </div>
            </div>
        </section>
    </SettingsLayout>
</template>