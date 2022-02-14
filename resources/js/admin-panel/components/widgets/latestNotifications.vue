<template>
    <div>
        <latest-notification v-for="latestNotification in latestNotifications"
                            :latest-notification="latestNotification"
                            :key="latestNotification.id"
                            :view-route="baseRoute + '/' + latestNotification.id"
                            :mark-as-read-string="markAsReadString"
                            :read-notifications="readNotifications"></latest-notification>
    </div>
</template>

<script>
    import LatestNotification from './latestNotification.vue';
    export default {
        props: ['getRoute', 'baseRoute', 'readNotifications'],
        components: {
            'latest-notification': LatestNotification,
        },
        data() {
            return {
                latestNotifications: [],
                markAsReadString: '',
            }
        },
        created() {
            axios.get(this.getRoute)
                .then((res) => {
                    this.latestNotifications = res.data.latestNotifications;
                    this.markAsReadString = res.data.markAsReadString;
                });
        }
    }
</script>