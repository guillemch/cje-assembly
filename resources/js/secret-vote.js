require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import VTooltip from 'v-tooltip';
import { ButtonPlugin, FormPlugin, ModalPlugin, FormGroupPlugin, FormRadioPlugin } from 'bootstrap-vue';
 
import SecretVote from './components/secret-vote/SecretVote';

Vue.use(VTooltip);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(ModalPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormRadioPlugin);

const vote = new Vue({
    components: {
      SecretVote
    },
    el: '#secretVote'
});
