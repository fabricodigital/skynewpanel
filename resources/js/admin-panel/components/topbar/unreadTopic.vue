<template>
    <li>
        <a :href="unreadTopic.view_route">
            <div class="pull-left text-center">
                <i class="fa fa-user text-light-blue" v-if="unreadTopic.type == 'direct'"></i>
                <i class="fa fa-question text-red" v-if="unreadTopic.type == 'help'"></i>
            </div>
            <h4>{{ topicSender }}
                <small><i class="fa fa-clock-o"></i> {{ unreadTopic.sent_at_formatted }}</small>
            </h4>
            <p>{{ unreadTopic.subject }}</p>
        </a>
    </li>
</template>

<script>
    export default {
        props: ['unreadTopic', 'authId'],
        computed: {
            topicSender() {
                if(this.unreadTopic.type == 'direct') {
                    if(this.unreadTopic.sender_id == this.authId) {
                        return this.unreadTopic.user_receiver.fullname;
                    }else{
                        return this.unreadTopic.sender.fullname;
                    }
                }else{
                    if(this.unreadTopic.sender_id == this.authId) {
                        return this.unreadTopic.role_receiver.name;
                    }else{
                        return this.unreadTopic.sender.fullname;
                    }
                }
            },
        }
    }
</script>