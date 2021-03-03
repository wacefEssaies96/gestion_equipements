require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate)
Vue.use(VueRouter);

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('add-product', require('./components/AddProductComponent.vue').default);
Vue.component('edit-product', require('./components/EditProductComponent.vue').default);

//Responsables
Vue.component('liste-responsable', require('./components/Responsables/Responsables.vue').default);
Vue.component('add-responsable', require('./components/Responsables/AddResponsable.vue').default);
Vue.component('edit-responsable', require('./components/Responsables/EditResponsable.vue').default);
//Hotlines
Vue.component('liste-hotline', require('./components/Hotlines/Hotlines.vue').default);
Vue.component('add-hotline', require('./components/Hotlines/AddHotline.vue').default);
Vue.component('edit-hotline', require('./components/Hotlines/EditHotline.vue').default);
//Techniciens
Vue.component('liste-technicien', require('./components/Techniciens/Techniciens.vue').default);
Vue.component('add-technicien', require('./components/Techniciens/AddTechnicien.vue').default);
Vue.component('edit-technicien', require('./components/Techniciens/EditTechnicien.vue').default);


import products from './components/ProductsComponent.vue';
import responsables from './components/Responsables/Responsables.vue';
import hotlines from './components/Hotlines/Hotlines.vue';
import techniciens from './components/Techniciens/Techniciens.vue';

const routes = [
    {path : '/responsables',component : responsables},
    {path : '/hotlines',component : hotlines},
    {path : '/techniciens',component : techniciens},
    {path: '/products', component: products},
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router: router,
});