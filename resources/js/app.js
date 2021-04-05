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

// //Responsables
// Vue.component('add-responsable', require('./components/Responsables/AddResponsable.vue').default);
// Vue.component('edit-responsable', require('./components/Responsables/EditResponsable.vue').default);
// Vue.component('delete-responsable', require('./components/Responsables/DeleteResponsable.vue').default);
//Productions
// Vue.component('add-production', require('./components/Productions/AddProduction.vue').default);
// Vue.component('edit-production', require('./components/Productions/EditProduction.vue').default);
// Vue.component('delete-production', require('./components/Productions/DeleteProduction.vue').default);
//Feedbacks
// Vue.component('add-feedback', require('./components/Feedbacks/AddFeedback.vue').default);
// Vue.component('edit-feedback', require('./components/Feedbacks/EditFeedback.vue').default);
// Vue.component('delete-feedback', require('./components/Feedbacks/DeleteFeedback.vue').default);
//Hotlines
// Vue.component('add-hotline', require('./components/Hotlines/AddHotline.vue').default);
// Vue.component('edit-hotline', require('./components/Hotlines/EditHotline.vue').default);
// Vue.component('delete-hotline', require('./components/Hotlines/DeleteHotline.vue').default);

// //Techniciens
// Vue.component('add-technicien', require('./components/Techniciens/AddTechnicien.vue').default);
// Vue.component('edit-technicien', require('./components/Techniciens/EditTechnicien.vue').default);
// Vue.component('delete-technicien', require('./components/Techniciens/DeleteTechnicien.vue').default);

//Historiques
// Vue.component('add-hist-admin', require('./components/Historiques/Admin/AddHistAdmin.vue').default);
// Vue.component('edit-hist-admin', require('./components/Historiques/Admin/EditHistAdmin.vue').default);

// Vue.component('edit-hist-tech', require('./components/Historiques/Tech/EditHistTech.vue').default);

// Vue.component('add-historique', require('./components/Historiques/Hotline/AddHistorique.vue').default);
// Vue.component('edit-historique', require('./components/Historiques/Hotline/EditHistorique.vue').default);
// Vue.component('delete-historique', require('./components/Historiques/DeleteHistoriques.vue').default);

//Equipements
// Vue.component('add-equipement', require('./components/Equipements/AddEquipement.vue').default);
// Vue.component('edit-equipement', require('./components/Equipements/EditEquipement.vue').default);
// Vue.component('delete-equipement', require('./components/Equipements/DeleteEquipement.vue').default);

// //Code Pannes
// Vue.component('add-codePanne', require('./components/Code_Pannes/AddCode_Panne.vue').default);
// Vue.component('edit-codePanne', require('./components/Code_Pannes/EditCode_Panne.vue').default);
// Vue.component('delete-codePanne', require('./components/Code_Pannes/DeleteCode_Panne.vue').default);

//OneDrive
// Vue.component('onedrive', require('./components/OneDrive/index.vue').default);

import dashboard from './components/Dashboard.vue';
import responsables from './components/Responsables/Responsables.vue';
import productions from './components/Productions/Productions.vue';
import feedbacks from './components/Feedbacks/Feedbacks.vue';
import hotlines from './components/Hotlines/Hotlines.vue';
import techniciens from './components/Techniciens/Techniciens.vue';
import equipements from './components/Equipements/Equipements.vue';
import code_pannes from './components/Code_Pannes/Code_Pannes.vue';
import histAdmin from './components/Historiques/Admin/Hist_admin.vue';
import histHotline from './components/Historiques/Hotline/Historiques.vue';
import histTechnicien from './components/Historiques/Tech/Hist_tech.vue';
import onedrive from './components/OneDrive/index.vue';


const routes = [
    {path : '/',component : dashboard},
    {path : '/responsables',component : responsables},
    {path : '/productions',component : productions},
    {path : '/feedbacks',component : feedbacks},
    {path : '/hotlines',component : hotlines},
    {path : '/techniciens',component : techniciens},
    {path : '/equipements',component : equipements},
    {path : '/code_pannes',component : code_pannes},
    {path : '/hist-admin',component : histAdmin},
    {path : '/hist-hotline',component : histHotline},
    {path : '/hist-technicien',component : histTechnicien},
    {path : '/onedrive',component : onedrive},
    
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router: router,
});