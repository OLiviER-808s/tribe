<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import GlobalSearch from '@/Components/Dropdowns/GlobalSearch.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'

const props = defineProps({
    profile: Object
})
</script>

<template>
    <AuthLayout>
        <template #header-right-section>
            <GlobalSearch />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <ProfileCard :profile="profile" with-inerests />
                </div>
            </section>
        </div>
    </AuthLayout>
</template>