window.Vue = require('vue');
//Global

//Topbar
Vue.component('topbar-unread-messages-counter', require('./topbar/unreadMessagesCount.vue'));
Vue.component('topbar-unread-notifications-counter', require('./topbar/unreadNotificationsCount.vue'));
Vue.component('topbar-unread-notifications-dropdown', require('./topbar/unreadNotifications.vue'));
Vue.component('topbar-unread-topics-dropdown', require('./topbar/unreadTopics.vue'));

//Sidebar
Vue.component('sidebar-unread-messages-counter', require('./sidebar/unreadMessagesCount.vue'));
Vue.component('sidebar-unread-notifications-counter', require('./sidebar/unreadNotificationsCount.vue'));
Vue.component('sidebar-unread-events-counter', require('./sidebar/unreadEventsCount.vue'));

//Messenger
Vue.component('make-messages-as-read', require('./admin/messenger/makeMessagesAsRead.vue'));

//Notifications
Vue.component('make-notification-as-read', require('./admin/notifications/makeNotificationAsRead.vue'));

//Widgets
Vue.component('latest-notifications', require('./widgets/latestNotifications.vue'));

//Discussion
Vue.component('model-discussion', require('./admin//discussion/modelDiscussion.vue'));
