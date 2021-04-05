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
                      <label for="num_bt">Num Bt</label>
                      <input type="text" class="form-control" placeholder="Num Bt" v-model="num_bt" 
                        :class="{'is-invalid':$v.num_bt.$invalid,'is-valid':!$v.num_bt.$invalid}"
                      >
                      <div class="valid-feedback">Num valide</div>
                      <div class="invalid-feedback">
                        <span v-if="!$v.num_bt.required">Veuillez entrer un Num Bt !</span>
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
                      <label for="jour">Jour</label>
                      <input type="date" class="form-control" placeholder="Jour" v-model="jour"
                      :class="{'is-invalid':$v.jour.$invalid, 'is-valid':!$v.jour.$invalid}">
                      <div class="valid-feedback">Jour validé</div>
                      <div class="invalid-feedback">
                        <span v-if="!$v.jour.required">Veuillez remplir ce champs !</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="zone">Zone</label>
                      <select class="form-control" v-model="zone"
                      :class="{'is-invalid':$v.zone.$invalid, 'is-valid':!$v.zone.$invalid}" @change="getTechniciens(zone)">
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
                </div>
                <div class="form-group">
                  <label for="tech">Techniciens</label>
                  <select class="form-control" v-model="tech_id"
                  :class="{'is-invalid':$v.tech_id.$invalid, 'is-valid':!$v.tech_id.$invalid}">
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
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Confirmer</button>
              </div>
            </form>
          </div>
        <!-- /.card -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button hidden id="submitEditHistorique" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { required } from 'vuelidate/lib/validators';
  export default {
    props: ['historiqueToEdit','tech'],
    data(){
      return{
        techniciens: {},
        num_bt: '',
        heure_demande: '',
        jour: '',
        zone: '',
        tech_id: '',
        baseUrl:process.env.MIX_URL,
      }
    },
    watch:{
      historiqueToEdit(newVal){
        this.num_bt = newVal[0].num_bt;
        this.heure_demande = newVal[0].heure_demande;
        this.jour = newVal[0].jour;
        this.zone = newVal[0].zone;
        this.tech_id = newVal[0].tech_id;
      },
      tech(newVal){
        this.techniciens = newVal;
      }
    },
    methods: {
      getTechniciens(zone){
        this.tech_id = '';
        axios.get(this.baseUrl+"/historiques/techniciens/"+zone)
        .then(response => this.techniciens=response.data) 
        .catch(error => console.log(error))
      },
      update(){
        axios.patch(this.baseUrl+'/historiques/edit/' + this.historiqueToEdit[0].id, {
            num_bt: this.num_bt,
            heure_demande: this.heure_demande,
            jour: this.jour,
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
      }
    },
  }
</script>