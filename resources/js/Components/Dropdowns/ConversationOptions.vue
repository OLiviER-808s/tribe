<script setup>
import {faEllipsisVertical, faFlag} from '@fortawesome/free-solid-svg-icons'
import DropdownMenu from 'v-dropdown-menu'
import IconButton from '../Generic/IconButton.vue'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {ModalsContainer, useModal} from "vue-final-modal";
import ReportConversationModal from "@/Components/Modals/ReportConversationModal.vue";

const props = defineProps({
    conversation: Object
})

const reportModal = useModal({
    component: ReportConversationModal,
    attrs: {
        conversation: props.conversation,
        onClose() {
            reportModal.close()
        }
    }
})
</script>

<template>
    <div>
        <ModalsContainer />

        <DropdownMenu :overlay="false" :dropup="false" direction="right" withDropdownCloser>
            <template #trigger>
                <IconButton :icon="faEllipsisVertical" variant="light" color="base" />
            </template>

            <template #body>
                <div class="max-w-64">
                    <div v-on:click="reportModal.open()" dropdown-closer class="text-md py-2 px-4 hover:bg-dropdown-select rounded-md cursor-pointer flex gap-2 items-center">
                        <FontAwesomeIcon :icon="faFlag" />
                        Report
                    </div>
                </div>
            </template>
        </DropdownMenu>
    </div>
</template>

<style scoped>
.v-dropdown-menu__container {
    @apply rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>
