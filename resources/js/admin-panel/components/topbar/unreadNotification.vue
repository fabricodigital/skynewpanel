<template>
    <li>
        <a :href="unreadNotification.view_route">{{ unreadNotification.title }}</a>
    </li>
</template>

<script>
    export default {
        props: ['unreadNotification'],
    }
</script>