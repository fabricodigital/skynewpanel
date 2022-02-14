<template>
    <span class="label bg-blue" v-if="sumCount > 0">{{ sumCount }}</span>
</template>

<script>
    export default {
        props: ['unreadMessages'],
        data: function () {
            return {
                unreadHelpMessagesCount: this.unreadMessages.help,
                unreadDirectMessagesCount: this.unreadMessages.direct,
            }
        },
        created() {
            Event.$on('topicRead', (unreadMessagesCount, infoMessage, topicId) => {
                this.setUnreadMessages(unreadMessagesCount);
            })
        },
        methods: {
            setUnreadMessages(unreadMessagesCount) {
                this.unreadHelpMessagesCount = unreadMessagesCount.help;
                this.unreadDirectMessagesCount = unreadMessagesCount.direct;
            }
        },
        computed: {
            sumCount() {
                return this.unreadHelpMessagesCount + this.unreadDirectMessagesCount;
            }
        }
    }
</script>
