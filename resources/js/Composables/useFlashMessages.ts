import {inject, provide, ref, watch} from "vue"
import {usePage} from "@inertiajs/vue3"
import { v4 as uuidv4 } from 'uuid'

export interface FlashMessage {
    uuid?: string
    color?: string
    content: string
    duration?: number
    opacity?: number
}

export const useFlashMessages = () => {
    const page = usePage()
    const flashMessages = ref<FlashMessage[]>([])

    const addFlashMessage = (data: FlashMessage) => {
        flashMessages.value.push({
            uuid: uuidv4(),
            ...data
        })
    }

    watch(page, (newPage) => {
        if (newPage.flash?.success) {
            addFlashMessage({
                content: newPage.flash?.success,
                color: 'success'
            })
        }

        if (newPage.flash?.error) {
            addFlashMessage({
                content: newPage.flash?.success,
                color: 'error'
            })
        }
    }, { deep: true, immediate: true })

    provide('flashMessages', { flashMessages, addFlashMessage })

    return flashMessages
}
