require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Amendments from './components/amendments/Amendments';

Vue.use(BootstrapVue);

const app = new Vue({
    components: {
        Amendments
    },

    el: '#amendments'
});
