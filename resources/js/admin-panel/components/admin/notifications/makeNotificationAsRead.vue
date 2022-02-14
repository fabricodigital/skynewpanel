<template>
    <div></div>
</template>

<script>
    export default {
        props: ['route'],
        data: function () {
            return {}
        },
        mounted() {
            axios.post(this.route, {})
                .then((res)=> {
                    Event.$emit('notificationRead', res.data.unreadNotificationsCount, res.data.notificationId, res.data.infoMessage);
                });
        }
    }
</script>