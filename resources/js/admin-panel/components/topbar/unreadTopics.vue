<template>
    <ul class="dropdown-menu">
        <li class="header">{{ infoMessage }}</li>
        <li>
            <ul class="menu">
                <topbar-unread-topic v-for="unreadTopic in unreadTopics"
                                     :unread-topic="unreadTopic"
                                     :key="unreadTopic.id"
                                     :auth-id="authId">
                </topbar-unread-topic>
                <li class="text-center" v-if="unreadTopics.length == 0">
                    <a href="#">{{ _t('No new messages') }}</a>
                </li>
            </ul>
        </li>
        <li class="footer"><a :href="viewAllRoute">{{ _t('View all') }}</a></li>
    </ul>
</template>

<script>
    import TopbarUnreadTopic from './unreadTopic.vue';
    export default {
        props: ['getRoute', 'viewAllRoute', 'baseRoute', 'authId'],
        components: {
            'topbar-unread-topic': TopbarUnreadTopic,
        },
        data() {
            return {
                unreadTopics: [],
                infoMessage: '',
            }
        },
        created() {
            Event.$on('topicRead', (unreadMessagesCount, infoMessage, topicId) => {
                let i = this.unreadTopics.map(item => item.id).indexOf(topicId) // find index of your object
                this.unreadTopics.splice(i, 1)
                this.infoMessage = infoMessage;
            });

            axios.get(this.getRoute)
                .then((res) => {
                    this.infoMessage = res.data.infoMessage;
                    this.unreadTopics = res.data.unreadTopics;
                });
        }
    }
</script>