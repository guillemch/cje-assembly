require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import VueSocketIO from 'vue-socket.io';
 
import Screen from './components/screen/Screen';

Vue.use(new VueSocketIO({
    debug: process.env.NODE_ENV === 'development',
    connection: process.env.MIX_SOCKETIO_SERVER
}));

const app = new Vue({
    components: {
        Screen
    },
    el: '#screen'
});
