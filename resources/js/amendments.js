require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueSocketIO from 'vue-socket.io';

import Amendments from './components/amendments/Amendments';

Vue.use(BootstrapVue);
Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://localhost:3000/'
}));

const app = new Vue({
    components: {
        Amendments
    },

    el: '#amendments'
});
