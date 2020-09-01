require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
 
import SecretVote from './components/secret-vote/SecretVote';

Vue.use(BootstrapVue);

const vote = new Vue({
    components: {
      SecretVote
    },
    el: '#secretVote'
});
