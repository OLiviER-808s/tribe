import {usePage} from "@inertiajs/vue3";
import {inject, provide, ref} from "vue";

export const useMessages = () => {
    const page = usePage()

    const unreadChats = ref(page.props.unreadChats.filter(chat => {
        return !page.url.includes(chat.uuid)
    }))
    provide('unreadChats', unreadChats)

    const chats = inject('chats')

    window.Echo.private(`user.${page.props.profile.uuid}`)
        .listen('.message-sent', (message) => {
            console.log(message)

            if (page.url !== `/chats/${message.chat_uuid}`) {
                const index = unreadChats.value.findIndex(chat => chat.uuid === message.chat_uuid)
                console.log(index)

                if (index >= 0) {
                    unreadChats.value[index].unreadMessages += 1
                } else {
                    unreadChats.value.push({
                        uuid: message.chat_uuid,
                        unreadMessages: 1
                    })
                }
            }

            if (chats) {
                const index = chats.findIndex(chat => chat.uuid === message.chat_uuid)

                if (index >= 0) {
                    chats[index].latestMessage = {
                        content: message.content,
                        sent_at: message.sent_at
                    }
                }
            }
        })
}
