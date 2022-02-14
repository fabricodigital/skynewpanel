<template>
    <div>
        <span class="mailbox-read-time pull-right" v-if="!isRead">
            <a href="#" @click="markAsRead()">{{ markAsReadString }}</a>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['latestNotification', 'readRoute', 'markAsReadString', 'read'],
        data: function () {
            return {
                isRead: this.read,
            }
        },
        methods: {
            markAsRead() {
                axios.post(this.readRoute, {})
                    .then((res)=> {
                        this.isRead = true;
                        Event.$emit('notificationRead', res.data.unreadNotificationsCount, res.data.notificationId, res.data.infoMessage);
                    });
            }
        }
    }
</script>