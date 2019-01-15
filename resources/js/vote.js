require('./bootstrap');

import API from './api';
window.API = new API();

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app'
});
