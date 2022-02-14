<template>
    <span class="label bg-green" v-if="unreadNotificationsCountLocal > 0">{{ unreadNotificationsCountLocal }}</span>
</template>

<script>
    export default {
        props: ['unreadNotificationsCount'],
        data: function () {
            return {
                unreadNotificationsCountLocal: null,
            }
        },
        mounted() {
            this.unreadNotificationsCountLocal = this.unreadNotificationsCount;
            Event.$on('notificationRead', (unreadNotificationsCount, notificationId, infoMessage) => {
                this.setUnreadNotifications(unreadNotificationsCount);
            })
        },
        methods: {
            setUnreadNotifications(unreadNotificationsCountLocal) {
                this.unreadNotificationsCountLocal = unreadNotificationsCountLocal;
            }
        },
    }
</script>