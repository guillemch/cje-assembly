require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueSocketIO from 'vue-socket.io';
 
import Vote from './components/vote/Vote';

Vue.use(BootstrapVue);
Vue.use(new VueSocketIO({
    debug: process.env.NODE_ENV === 'development',
    connection: process.env.MIX_SOCKETIO_SERVER
}));

const app = new Vue({
    components: {
        Vote
    },
    el: '#vote'
});
