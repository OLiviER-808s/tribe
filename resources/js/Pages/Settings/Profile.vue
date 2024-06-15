<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue'
import ProfileEditSection from "@/Components/Settings/ProfileEditSection.vue"
import InterestEditSection from "@/Components/Settings/InterestEditSection.vue"

const props = defineProps({
    categories: Array
})
</script>

<template>
    <SettingsLayout>
        <section class="flex justify-center pt-8">
            <div class="w-full max-w-2xl p-2 flex flex-col gap-12">
                <ProfileEditSection />

                <InterestEditSection :categories="categories" />
            </div>
        </section>
    </SettingsLayout>
</template>
