require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate';
import vSelect from 'vue-select';

Vue.use(Vuelidate)
Vue.use(VueRouter);

Vue.component('v-select', vSelect);
Vue.component('pagination', require('laravel-vue-pagination'));

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

//Historiques
Vue.component('liste-hist-admin', require('./components/Historiques/Admin/Hist_admin.vue').default);
Vue.component('edit-hist-admin', require('./components/Historiques/Admin/EditHistAdmin.vue').default);

Vue.component('liste-hist-tech', require('./components/Historiques/Tech/Hist_tech.vue').default);
Vue.component('edit-hist-tech', require('./components/Historiques/Tech/EditHistTech.vue').default);

Vue.component('add-historique', require('./components/Historiques/Hotline/AddHistorique.vue').default);
Vue.component('edit-historique', require('./components/Historiques/Hotline/EditHistorique.vue').default);
Vue.component('liste-historique', require('./components/Historiques/Hotline/Historiques.vue').default);

//Equipements
Vue.component('liste-equipement', require('./components/Equipements/Equipements.vue').default);
Vue.component('add-equipement', require('./components/Equipements/AddEquipement.vue').default);
Vue.component('edit-equipement', require('./components/Equipements/EditEquipement.vue').default);

//Code Pannes
Vue.component('liste-code_panne', require('./components/Code_Pannes/Code_Pannes.vue').default);
Vue.component('add-codePanne', require('./components/Code_Pannes/AddCode_Panne.vue').default);
Vue.component('edit-codePanne', require('./components/Code_Pannes/EditCode_Panne.vue').default);

// import responsables from './components/Responsables/Responsables.vue';
// import hotlines from './components/Hotlines/Hotlines.vue';
// import techniciens from './components/Techniciens/Techniciens.vue';
// import historiques from './components/Historiques/Historiques.vue';
// import equipements from './components/Equipements/Equipements.vue';
// import code_pannes from './components/Code_Pannes/Code_Pannes.vue';



const routes = [
    // {path : '/responsables',component : responsables},
    // {path : '/hotlines',component : hotlines},
    // {path : '/techniciens',component : techniciens},
    // {path : '/historiques',component : historiques},
    // {path : '/equipements',component : equipements},
    // {path : '/code_pannes',component : code_pannes},
];

const router = new VueRouter({routes});

const app = new Vue({
    el: '#app',
    router: router,
});