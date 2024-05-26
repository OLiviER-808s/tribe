import {usePage} from "@inertiajs/vue3";
import {provide, ref} from "vue";

export const useMessages = () => {
    const page = usePage()

    const unreadChats = ref(page.props.unreadChats)

    provide('unreadChats', unreadChats)

    window.Echo.private(`user.${page.props.profile.uuid}`)
        .listen('.message-sent', (message) => {
            console.log(message)

            if (page.url !== `/chats/${message.chat_uuid}`) {
                const index = unreadChats.value.findIndex(chat => chat.uuid === message.chat_uuid)
                console.log(index)

                if (index !== null) {
                    unreadChats.value[index].unreadMessages += 1
                } else {
                    unreadChats.value.push({
                        uuid: message.chat_uuid,
                        unreadMessages: 1
                    })
                }
            }
        })
}
