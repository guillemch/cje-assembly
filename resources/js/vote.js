require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Vote from './components/vote/Vote';

Vue.use(BootstrapVue);

const app = new Vue({
    components: {
        Vote
    },

    el: '#vote'
});
