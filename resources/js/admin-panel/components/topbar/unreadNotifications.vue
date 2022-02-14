<template>
    <ul class="dropdown-menu">
        <li class="header">{{ infoMessage }}</li>
        <li>
            <ul class="menu">
                <topbar-unread-notification v-for="unreadNotification in unreadNotifications"
                                            :unread-notification="unreadNotification"
                                            :key="unreadNotification.id"></topbar-unread-notification>
                <li class="text-center" v-if="unreadNotifications.length == 0">
                    <a href="#">{{ _t('No new notifications') }}</a>
                </li>
            </ul>
        </li>
        <li class="footer"><a :href="viewAllRoute">{{ _t('View all') }}</a></li>
    </ul>
</template>


<script>
    import TopbarUnreadNotification from './unreadNotification.vue';

    export default {
        props: ['getRoute', 'viewAllRoute', 'baseRoute'],
        components: {
            'topbar-unread-notification': TopbarUnreadNotification,
        },
        data() {
            return {
                unreadNotifications: [],
                infoMessage: '',
            }
        },
        created() {
            Event.$on('notificationRead', (unreadNotificationsCount, notificationId, infoMessage) => {
                let i = this.unreadNotifications.map(item => item.id).indexOf(notificationId)
                this.unreadNotifications.splice(i, 1)
                this.infoMessage = infoMessage;

            });

            axios.get(this.getRoute)
                .then((res) => {
                    this.infoMessage = res.data.infoMessage;
                    this.unreadNotifications = res.data.unreadNotifications;
                });
        }
    }
</script>