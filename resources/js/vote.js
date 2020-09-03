require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue';
import VTooltip from 'v-tooltip';
import { ButtonPlugin, FormPlugin, ModalPlugin, FormGroupPlugin, FormRadioPlugin, FormInputPlugin, InputGroupPlugin } from 'bootstrap-vue';
import VueSocketIO from 'vue-socket.io';
 
import Vote from './components/vote/Vote';

Vue.use(VTooltip);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(ModalPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormRadioPlugin);
Vue.use(FormInputPlugin);
Vue.use(InputGroupPlugin);
Vue.use(new VueSocketIO({
    debug: process.env.NODE_ENV === 'development',
    connection: process.env.MIX_SOCKETIO_SERVER
}));

const vote = new Vue({
    components: {
        Vote
    },
    el: '#vote'
});
