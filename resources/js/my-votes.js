require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
 
import MyVotes from './components/my-votes/MyVotes';

Vue.use(BootstrapVue);

const myVotes = new Vue({
    components: {
        MyVotes
    },

    el: '#myVotes'
});
