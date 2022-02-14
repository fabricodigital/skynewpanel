<template>
    <div class="box box-success widget widget--notifications">
        <div class="box-header">
            <i class="fa fa-thumb-tack"></i>
            <h3 class="box-title">{{ latestNotification.title }}</h3>
            <span class="mailbox-read-time pull-right">
                <time :datetime="latestNotification.start">{{ latestNotification.start_formatted }}</time>
                -
                <time :datetime="latestNotification.end">{{ latestNotification.end_formatted }}</time>
            </span>
        </div>

        <div class="box-body" >
            <div v-html="latestNotification.text">
                {{ latestNotification.text }}
            </div>
            <div v-html="latestNotification.attachments_template">
                {{ latestNotification.attachments_template }}
            </div>
            <mark-as-read-button
                    :latest-notification="latestNotification"
                    :read-route="latestNotification.read_route"
                    :mark-as-read-string="markAsReadString"
                    :read="read"></mark-as-read-button>

        </div>

    </div>
</template>

<script>
    import MarkAsReadButton from '../admin/notifications/markAsReadButton.vue';
    export default {
        props: ['latestNotification', 'viewRoute', 'markAsReadString', 'readNotifications'],
        components: {
            'mark-as-read-button': MarkAsReadButton,
        },
        computed: {
            read() {
                for (let n in this.readNotifications) {
                    let readNotification = this.readNotifications[n];
                    if(readNotification.id == this.latestNotification.id) {
                        return true;
                    }
                }

                return false;
            }
        }
    }
</script>