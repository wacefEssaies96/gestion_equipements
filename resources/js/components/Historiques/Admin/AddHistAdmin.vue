<template>
<div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau historique</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Formulaire</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="checkAddForm">
                <div class="card-body">
                  <div class="card card-info">
                    <div class="card-header">
                      <div class="card-title">
                        <h3 class="card-title">Hotline</h3>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="zone">Zone</label>
                                <select class="form-control" v-model="zone"
                                :class="{'is-invalid':(zone != '') ? $v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}" @change="getTechniciens(zone)">
                                    <option value="Assemblage">Assemblage</option>
                                    <option value="Sertissage">Sertissage</option>
                                    <option value="Préparation">Préparation</option>
                                    <option value="Coupe">Coupe</option>
                                    <option value="Controle éléctrique">Controle éléctrique</option>
                                </select>
                                <div class="valid-feedback">Zone validé</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.zone.required">Veuillez choisir une zone !</span>
                                </div>
                          </div>
                          <div class="form-group">
                            <label for="type_travaille">Type de travaille</label>
                            <select v-model="type_travaille" class="form-control"
                            :class="{'is-invalid':(type_travaille != '') ? $v.type_travaille.$invalid:'', 'is-valid':!$v.type_travaille.$invalid}">
                                <option value="">Choisir le type de travaille</option>
                                <option value="C">C</option>
                                <option value="S">S</option>
                                <option value="CS">CS</option>
                                <option value="PS">PS</option>
                            </select>
                            <div class="valid-feedback">validé</div>
                            <div class="invalid-feedback">
                              <span v-if="!$v.type_travaille.required">Veuillez remplir ce champs !</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="heure_demande">Heure de demande</label>
                            <input type="time" class="form-control" placeholder="Heure de demande" v-model="heure_demande"
                            :class="{'is-invalid':(heure_demande != '') ?$v.heure_demande.$invalid:'', 'is-valid':!$v.heure_demande.$invalid}">
                            <div class="valid-feedback">Heure de demande valide</div>
                            <div class="invalid-feedback">
                              <span v-if="!$v.heure_demande.required">Veuillez mettre une date !</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="tech">Techniciens</label>
                              <select class="form-control" v-model="tech_id"
                              :class="{'is-invalid':(tech_id != '') ?$v.tech_id.$invalid:'', 'is-valid':!$v.tech_id.$invalid}"
                              >
                                <option v-for="technicien in techniciens" :key="technicien.user_id" :value="technicien.user_id">
                                  {{technicien.user.nom}} {{technicien.user.prenom}}
                                </option>
                              </select>
                              <div class="valid-feedback">Technicien validé</div>
                              <div class="invalid-feedback">
                                  <span v-if="!$v.tech_id.required">Veuillez choisir une option !</span>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="description_demande">Description demandé</label>
                            <input type="text" class="form-control" placeholder="Description demandé " v-model="description_demande"
                            :class="{'is-invalid':(description_demande != '') ? $v.description_demande.$invalid:'', 'is-valid':!$v.description_demande.$invalid}">
                            <div class="valid-feedback">validé</div>
                            <div class="invalid-feedback">
                              <span v-if="!$v.description_demande.required">Veuillez remplir ce champs !</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="code machine">Code Machine</label>
                              <select class="form-control" v-model="code_machine"
                              :class="{'is-invalid':(code_machine!= '') ? $v.code_machine.$invalid:'', 'is-valid':!$v.code_machine.$invalid}"
                              >
                                <option v-for="equip in e" :key="equip.id" :value="equip.id">
                                  {{equip.code}}
                                </option>
                              </select>
                              <div class="valid-feedback">validé</div>
                              <div class="invalid-feedback">
                                  <span v-if="!$v.code_machine.required">Veuillez choisir une option !</span>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title">Technicien</h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="code_panne">Code panne</label>
                            <v-select v-model="code_panne" :multiple="true" 
                                :options="c" 
                                :get-option-label="option => option.code+' '+option.designation"
                                :reduce='option => option.id'></v-select>
                          </div> 
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="piece_rechange">Pièce de rechange</label>
                            <input type="text" class="form-control" placeholder="Pièce de rechange" v-model="piece_rechange">
                          </div>

                        </div>
                      </div>
                      <div class="form-group">
                        <template v-if="zone == 'Assemblage'">
                          <label for="num_planche">Num Planche</label>
                          <input type="text" class="form-control" v-model="num_planche">
                        </template>
                        <template v-if="zone == 'Controle éléctrique'">
                          <label for="nom_support">Nom Support</label>
                          <input type="text" class="form-control" v-model="nom_support">
                        </template>
                      </div>
                      <div class="form-group">
                        <label for="travaille">Travaille effectué</label>
                        <textarea class="form-control" v-model="travaille"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <button hidden type="submit" id="submitAddHistorique" class="btn btn-primary" @click="historiqueStore" data-dismiss="modal"></button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
import { required } from 'vuelidate/lib/validators';
  export default {
      data(){
        return{
          techniciens: {},
          e:{},
          c:[],
          heure_demande: '',
          zone: '',
          tech_id: '',
          travaille: '',
          piece_rechange: '',
          code_machine: '',
          code_panne: '',
          num_planche: '',
          type_travaille:'',
          nom_support:'',
          type_travaille: '',
          nom_support:'',
          description_demande:'',
        }
    },
    methods:{
      getTechniciens(zone){
          this.tech_id = '';
          axios.get("/historiques/techniciens/"+zone)
          .then(response => this.techniciens=response.data) 
          .catch(error => console.log(error));
          this.getEquipements(zone);
      },
      getEquipements(zone){
        this.code_machine = '';
        axios.get('/historiques/equipement/zone/' + zone)
        .then(response =>this.e =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        this.code_panne = ''
        axios.get("/historiques/code-panne/"+zone)
        .then(response => this.c = response.data)
        .catch(error => console.log(error))
      },
      checkAddForm(){
        this.$v.$touch()
        if(!this.$v.$invalid){
          var submitForm = document.getElementById('submitAddHistorique');
          submitForm.click();
        }else{
            alert("Veuillez remplir les champs correctement !");
        }
      },
      historiqueStore(){
        console.log(this.travaille+' '+this.piece_rechange)
        axios.post('/historiques',{

          heure_demande: this.heure_demande,
          zone: this.zone,
          tech_id: this.tech_id,
          code_equip: this.code_machine,
          description_demande: this.description_demande,

          travaille: this.travaille,
          piece_rechange: this.piece_rechange,
          num_planche: this.num_planche,
          type_travaille: this.type_travaille,
          nom_support: this.nom_support,
          code_panne: this.code_panne,
        })
        .then(response => this.$emit('historique-added',response))
        .catch(error => console.log(error));
        this.refreshData();
      },
      refreshData(){
        this.heure_demande= '';
        this.description_demande= '';
        this.zone= '';
        this.tech_id= '';
        this.heure_fin= '';
        this.travaille= '';
        this.piece_rechange= '';
        this.code_panne= '';
        this.code_machine= '';
        this.num_planche= '';
        this.type_travaille= '';
        this.nom_support= '';
      }
    },
    validations: {
      description_demande:{
        required
      },
      heure_demande: {
        required,
      },
      code_machine: {
        required
      },
      zone: {
        required,
      },
      tech_id:{
        required,
      },
      type_travaille:{
        required 
      },
      travaille: {

      },
      piece_rechange: {

      },
      code_panne: {

      },
      num_planche: {
          
      },
      nom_support:{
          
      },
      
    },
  }
</script>
