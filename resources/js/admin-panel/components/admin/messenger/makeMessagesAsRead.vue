<template>
    <div></div>
</template>

<script>
    export default {
        props: ['topic', 'route'],
        data: function () {
            return {}
        },
        mounted: function () {
            axios.post(this.route, {})
                .then((res)=> {
                    Event.$emit('topicRead', res.data.unreadMessagesCount, res.data.infoMessage, res.data.topicId);
                });
        }
    }
</script>