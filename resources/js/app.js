require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate';
import vSelect from 'vue-select';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);
Vue.use(Vuelidate)
Vue.use(VueRouter);

Vue.component('v-select', vSelect);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('navbar', require('./components/Layout/NavBar.vue').default);

import Vue from 'vue';
import dashboard from './components/Dashboard.vue';
import feedbacks from './components/Feedbacks/Feedbacks.vue';
import zones from './components/Zones/Zones.vue';
import equipements from './components/Equipements/Equipements.vue';
import code_pannes from './components/Code_Pannes/Code_Pannes.vue';
import histAdmin from './components/Historiques/Admin/Hist_admin.vue';
import histHotline from './components/Historiques/Hotline/Historiques.vue';
import histTechnicien from './components/Historiques/Tech/Hist_tech.vue';
import onedrive from './components/OneDrive/index.vue';
import users from './components/Users/Users.vue'
import globalChat from './components/Chat/GlobalChat.vue';

const routes = [
    { path: '/', component: dashboard },
    { path: '/feedbacks', component: feedbacks },
    { path: '/zones', component: zones },
    { path: '/equipements', component: equipements },
    { path: '/code_pannes', component: code_pannes },
    { path: '/hist-admin', component: histAdmin },
    { path: '/hist-hotline', component: histHotline },
    { path: '/hist-technicien', component: histTechnicien },
    { path: '/onedrive', component: onedrive },
    { path: '/users', component: users },
    { path: '/chat', component: globalChat },
];

const router = new VueRouter({ routes });

const app = new Vue({
    el: '#app',
    router: router,
});