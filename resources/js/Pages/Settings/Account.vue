<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import Button from '@/Components/Generic/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faRightFromBracket } from '@fortawesome/free-solid-svg-icons'
import { router } from '@inertiajs/vue3'
import ChangePassword from '@/Components/Settings/ChangePassword.vue'
import { ModalsContainer, useModal } from 'vue-final-modal'
import DeleteAccountModal from '@/Components/Modals/DeleteAccountModal.vue'

const logout = () => router.post(route('logout'))

const deleteModal = useModal({
    component: DeleteAccountModal,
    attrs: {
        onClose() {
            deleteModal.close()
        }
    }
})
</script>

<template>
    <SettingsLayout>
        <template #title>
            <h1>Account Settings</h1>
        </template>

        <section class="flex justify-center pt-8">
            <ModalsContainer />

            <div class="w-full max-w-2xl p-2 flex flex-col gap-12">
                <!-- Logout -->
                <div>
                    <h2 class="text-2xl font-medium mb-4">Logout</h2>

                    <div class="flex items-center justify-between">
                        <p>Logged in as <span class="font-medium">{{ $page.props.auth.user.email }}</span></p>

                        <Button :on-click="logout" color="secondary" variant="light" styles="text-sm">
                            <FontAwesomeIcon :icon="faRightFromBracket" /> Logout
                        </Button>
                    </div>
                </div>

                <!-- Change Password -->
                <div v-if="!$page.props.auth.isOAuthUser">
                    <h2 class="text-2xl font-medium mb-4">Update Password</h2>

                    <ChangePassword />
                </div>

                <!-- Delete Account -->
                <div>
                    <h2 class="text-2xl font-medium mb-4">Delete Account</h2>
                    <p class="mb-2">Once you delete your account, there is no going back. Please be certain.</p>
                    <Button color="error" styles="text-sm" :on-click="deleteModal.open">Delete your Account</Button>
                </div>
            </div>
        </section>
    </SettingsLayout>
</template>