require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
Vue.use(VueRouter);

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('add-product', require('./components/AddProductComponent.vue').default);
Vue.component('edit-product', require('./components/EditProductComponent.vue').default);

Vue.component('liste', require('./components/Responsables/Responsables.vue').default);
Vue.component('add-responsable', require('./components/Responsables/AddResponsable.vue').default);
Vue.component('edit-responsable', require('./components/Responsables/EditResponsable.vue').default);

import products from './components/ProductsComponent.vue';
import responsables from './components/Responsables/Responsables.vue';

const routes = [
    {path : '/responsables',component : responsables},
    {path: '/products', component: products},
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router: router,
});