<template>
<div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Ajouter un nouveau historique
        </button>
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
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="num_bt">Num Bt</label>
                        <input type="text" class="form-control" placeholder="Num Bt" v-model="num_bt" 
                          :class="{'is-invalid':(num_bt != '') ? $v.num_bt.$invalid:'','is-valid':!$v.num_bt.$invalid}"
                        >
                        <div class="valid-feedback">Num valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.num_bt.required">Veuillez entrer un Num Bt !</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="heure_demande">Heure de demande</label>
                        <input type="time" class="form-control" placeholder="Heure de demande" v-model="heure_demande"
                        :class="{'is-invalid':(heure_demande != '') ?$v.heure_demande.$invalid:'', 'is-valid':!$v.heure_demande.$invalid}">
                        <div class="valid-feedback">Heure de demande valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.heure_demande.required">Veuillez mettre une date !</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="jour">Jour</label>
                        <input type="date" class="form-control" placeholder="Jour" v-model="jour"
                        :class="{'is-invalid':(jour != '') ?$v.jour.$invalid:'', 'is-valid':!$v.jour.$invalid}">
                        <div class="valid-feedback">Jour validé</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.jour.required">Veuillez remplir ce champs !</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="zone">Zone</label>
                            <select class="form-control" v-model="zone"
                            :class="{'is-invalid':(zone != '') ?$v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}" @change="getTechniciens(zone)">
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
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="tech">Techniciens</label>
                          <select id="selectTech" class="form-control" v-model="tech_id"
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
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="heure_fin">Heure de fin de travaille</label>
                            <input type="time" class="form-control" placeholder="Heure de fin de travaille" v-model="heure_fin">
                        </div>
                        <div class="form-group">
                            <label for="piece_rechange">Pièce de rechange</label>
                            <input type="text" class="form-control" placeholder="Pièce de rechange" v-model="piece_rechange">
                        </div>      
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                            <label for="code_machine">Code machine</label>
                            <select class="form-control" v-model="code_machine">
                                <option v-for="equipement in e" :key="equipement.id" :value="equipement.id">
                                    {{equipement.code}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="code_panne">Code panne</label>
                            <v-select v-model="code_panne" :multiple="true" 
                                :options="c" 
                                :get-option-label='option => option.code'
                                :reduce='option => option.id'></v-select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                          <label for="travaille">Travaille effectué</label>
                          <textarea class="form-control" placeholder="Travaille éffectué" v-model="travaille"></textarea>
                      </div>
                      <div class="form-group">
                        <template v-if="zone == 'Assemblage'">
                          <label for="num_planche">Num Planche</label>
                          <input type="text" class="form-control" v-model="num_planche">
                        </template>
                        <template v-if="zone == 'Sertissage'">
                          <label for="type_travaille">Type de travaille</label>
                            <select v-model="type_travaille" class="form-control"
                            :class="{'is-invalid':$v.type_travaille.$invalid, 'is-valid':!$v.type_travaille.$invalid}">
                                <option value="">Choisir le type de travaille</option>
                                <option value="C">C</option>
                                <option value="P">P</option>
                                <option value="PH">PH</option>
                                <option value="S">S</option>
                            </select>
                        </template>
                        <template v-if="zone == 'Controle éléctrique'">
                          <label for="nom_support">Nom Support</label>
                          <input type="text" class="form-control" v-model="nom_support">
                        </template>
                      </div>
                      <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea class="form-control" v-model="commentaire"></textarea>
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
          num_bt: '',
          heure_demande: '',
          jour: '',
          zone: '',
          tech_id: '',
          heure_fin: '',
          travaille: '',
          commentaire: '',
          piece_rechange: '',
          code_machine: '',
          code_panne: '',
          num_planche: '',
          type_travaille:'',
          nom_support:'',
          options:[],
          type_travaille: '',
          nom_support:'',
          e:{},
          c:[],
        }
    },
    methods:{
      getTechniciens(zone){
          this.tech_id = '';
          axios.get("http://localhost:8000/historiques/techniciens/"+zone)
          .then(response => this.techniciens=response.data) 
          .catch(error => console.log(error));
          this.getEquipements(zone);
      },
      getEquipements(zone){
        this.code_machine = '';
        axios.get('http://localhost:8000/historiques/equipement/zone/' + zone)
        .then(response =>this.e =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        this.code_panne = ''
        axios.get("http://localhost:8000/historiques/code-panne/"+zone)
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
        axios.post('http://localhost:8000/historiques',{
            num_bt: this.num_bt,
            heure_demande: this.heure_demande,
            jour: this.jour,
            zone: this.zone,
            tech_id: this.tech_id,
            heure_fin: this.heure_fin,
            travaille: this.travaille,
            piece_rechange: this.piece_rechange,
            commentaire: this.commentaire,
            code_panne: this.code_panne,
            code_equip: this.code_machine,
            num_planche: this.num_planche,
            type_travaille: this.type_travaille,
            nom_support: this.nom_support
        })
        .then(response => this.$emit('historique-added',response))
        .catch(error => console.log(error));
      },
    },
    validations: {
      num_bt: {
        required,
      },
      heure_demande: {
        required,
      },
      jour: {
        required,
      },
      zone: {
        required,
      },
      tech_id:{
          required,
      },
      heure_fin:{
      },
      travaille: {
      },
      commentaire: {
      },
      piece_rechange: {
      },
      code_machine: {
      },
      code_panne: {
      },
      num_planche: {
          
      },
      type_travaille:{
          
      },
      nom_support:{
          
      }
    },
  }
</script>
