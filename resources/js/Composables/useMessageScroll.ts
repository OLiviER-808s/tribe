import {nextTick, ref, watch} from "vue";
import {useInfiniteScroll} from "@vueuse/core";

export const useMessageScroll = (props: any) => {
    const feedContainer = ref(null)

    const nextCursor = ref(null)
    const loading = ref(false)

    const scrollToBottom = () => {
        if (feedContainer.value && (feedContainer.value.scrollHeight - feedContainer.value.clientHeight <= feedContainer.value.scrollTop + 50)) {
            nextTick(() => {
                feedContainer.value.scrollTop = feedContainer.value.scrollHeight
            })
        }
    }

    watch(feedContainer, () => {
        if (feedContainer.value) {
            feedContainer.value.scrollTop = feedContainer.value.scrollHeight
        }
    }, { immediate: true })

    useInfiniteScroll(feedContainer, async () => {
        if (loading.value || !nextCursor.value) return

        loading.value = true
        const response = await axios.get(`${props.messages.meta.path}?cursor=${nextCursor.value}`)

        messagesState.value = [...messagesState.value, ...response.data.data]
        nextCursor.value = response.data.meta.next_cursor
        loading.value = false
    }, { distance: 200, direction: 'top' })

    return { feedContainer, scrollToBottom }
}
