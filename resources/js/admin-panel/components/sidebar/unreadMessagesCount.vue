<template>
    <span class="pull-right-container">
        <small class="label pull-right bg-red" v-if="unreadHelpMessagesCount > 0 ">{{ unreadHelpMessagesCount }}</small>
        <small class="label pull-right bg-light-blue" v-if="unreadDirectMessagesCount > 0">{{ unreadDirectMessagesCount }}</small>
    </span>
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
            Event.$on('topicRead', (unreadMessagesCount) => {
                this.setUnreadMessages(unreadMessagesCount);
            })
        },
        methods: {
            setUnreadMessages(unreadMessagesCount) {
                this.unreadHelpMessagesCount = unreadMessagesCount.help;
                this.unreadDirectMessagesCount = unreadMessagesCount.direct;
            }
        },
    }
</script>
