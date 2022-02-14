<template>
    <span class="pull-right-container">
        <small class="label pull-right bg-green" v-if="unreadEventsCount > 0">{{ unreadEventsCount}}</small>
    </span>
</template>

<script>
    export default {
        props: ['initUnreadEventsCount'],
        data: function () {
            return {
                unreadEventsCount: this.initUnreadEventsCount
            }
        },
        created() {
            Event.$on('eventRead', () => {
                this.unreadEventsCount --;
            })
        },
    }
</script>
