/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('meeting', require('./components/Meeting.vue').default);


import Notifications from 'vue-notification'
Vue.use(Notifications)


const app = new Vue({
    el: '#app',
});
