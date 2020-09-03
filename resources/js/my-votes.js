require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import { ModalPlugin } from 'bootstrap-vue';
 
import MyVotes from './components/my-votes/MyVotes';

Vue.use(ModalPlugin);

const myVotes = new Vue({
    components: {
        MyVotes
    },

    el: '#myVotes'
});
