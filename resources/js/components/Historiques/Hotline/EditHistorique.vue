<template>
  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalTitle">Modifier un historique</h5>
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
            <form @submit.prevent="checkEditForm">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label for="zone">Zone</label>
                            <select class="form-control" v-model="zone"
                            :class="{'is-invalid':(zone != '') ? $v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}" @change="getData(zone)">
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
                      <label for="heure_demande">Heure de demande</label>
                      <input type="time" class="form-control" placeholder="Heure de demande" v-model="heure_demande"
                      :class="{'is-invalid':$v.heure_demande.$invalid, 'is-valid':!$v.heure_demande.$invalid}">
                      <div class="valid-feedback">Heure de demande valide</div>
                      <div class="invalid-feedback">
                        <span v-if="!$v.heure_demande.required">Veuillez mettre une date !</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
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
                        <label for="tech">Techniciens</label>
                        <select class="form-control" v-model="tech_id"
                        :class="{'is-invalid':(tech_id != '') ? $v.tech_id.$invalid:'', 'is-valid':!$v.tech_id.$invalid}"
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
                 <div class="form-group">
                    <label>Type de travaille</label>
                    <select class="form-control" v-model="type_travaille"
                    :class="{'is-invalid':(type_travaille != '') ? $v.type_travaille.$invalid:'', 'is-valid':!$v.type_travaille.$invalid}"
                    >
                      <option value="C">C</option>
                      <option value="PS">PS</option>
                      <option value="CS">CS</option>
                      <option value="S">S</option>
                    </select>
                    <div class="valid-feedback">validé</div>
                    <div class="invalid-feedback">
                        <span v-if="!$v.type_travaille.required">Veuillez choisir une option !</span>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="code_equip">Code machine</label>
                      <select class="form-control" v-model="code_equip"
                      :class="{'is-invalid':(code_equip != '') ? $v.code_equip.$invalid:'', 'is-valid':!$v.code_equip.$invalid}">
                          <option v-for="e in equipements_list" :key="e.id" :value="e.id">
                              {{e.code}}
                          </option>
                      </select>
                      <div class="valid-feedback">Validé</div>
                      <div class="invalid-feedback">
                          <span v-if="!$v.code_equip.required">Veuillez choisir une option !</span>
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
          <button hidden id="submitEditHistorique" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { required } from 'vuelidate/lib/validators';
  export default {
    props: ['historiqueToEdit','tech','equipements'],
    data(){
      return{
        techniciens: {},
        heure_demande: '',
        zone: '',
        tech_id: '',
        description_demande:'',
        type_travaille:'',
        code_equip:'',
        equipements_list:{}
      }
    },
    watch:{
      historiqueToEdit(newVal){
        this.heure_demande = newVal[0].heure_demande;
        this.zone = newVal[0].zone;
        this.tech_id = newVal[0].tech_id;
        this.description_demande = newVal[0].description_demande;
        this.type_travaille = newVal[0].type_travaille;
        this.code_equip = newVal[0].code_equip;
      },
      tech(newVal){
        this.techniciens = newVal;
      },
      equipements(newVal){
        this.equipements_list = newVal;
      }
    },
    methods: {
      async getData(zone){
        this.tech_id = '';
        this.code_equip ='';
        await axios.get("/historiques/techniciens/"+zone)
        .then(response => this.techniciens=response.data) 
        .catch(error => console.log(error));
        await axios.get('/historiques/equipement/zone/' + zone)
        .then(response =>this.equipements_list =  response.data)
        .catch(error => console.log(error));
      },
      update(){
        axios.patch('/historiques/edit/' + this.historiqueToEdit[0].id, {
            heure_demande: this.heure_demande,
            description_demande: this.description_demande,
            type_travaille: this.type_travaille,
            code_equip: this.code_equip,
            zone: this.zone,
            tech_id: this.tech_id,
        })
        .then(response => this.$emit('historique-updated',response))
        .catch(error => console.log(error));
      },
      checkEditForm(){
        this.$v.$touch()
        if(!this.$v.$invalid){
          var submitForm = document.getElementById('submitEditHistorique');
          submitForm.click();
        }
        else{
          alert("Veuillez remplir les champs correctement !");
        }
      },
    },
    validations: {
      heure_demande: {
        required,
      },
      type_travaille: {
        required,
      },
      zone: {
        required,
      },
      tech_id:{
          required,
      },
      description_demande:{
        required
      },
      code_equip:{
        required
      }
    },
  }
</script>