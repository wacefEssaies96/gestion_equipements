<template>
     <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Modifier un feedback</h5>
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
                            :class="{'is-invalid':(zone != '') ? $v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}" @change="getEquipements(zone)">
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
                            <label for="equipement">Equipement</label>
                              <select class="form-control" v-model="code_machine"
                              :class="{'is-invalid':$v.code_machine.$invalid, 'is-valid':!$v.code_machine.$invalid}">
                                  <option v-for="equipement in e" :key="equipement.id" :value="equipement.id">
                                      {{equipement.nom}}
                                  </option>
                              </select>
                            <div class="valid-feedback">equipement validé</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.code_machine.required">Veuillez choisir un equipement !</span>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="commentaire">Commentaire</label>
                            <textarea id="commentaire" class="form-control" placeholder="commentaire" v-model="commentaire"
                            :class="{'is-invalid':(commentaire != '') ?$v.commentaire.$invalid:'', 'is-valid':!$v.commentaire.$invalid}"></textarea>
                            <div class="valid-feedback">Validé</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.commentaire.required">Veuillez remplir ce champs !</span>
                                <span v-if="!$v.commentaire.minLength">Veuillez entrer au moins 3 caractères !</span>
                                <span v-if="!$v.commentaire.maxLength">Maximum 190 caractères !</span>
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
                <button type="button" class="btn btn-secondary" @click="changermdp('true')" data-dismiss="modal">Fermer</button>
                <button hidden id="submitEditFeedback" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update">Confirmer</button>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
import { required } from 'vuelidate/lib/validators';
    export default {
      props: ['feedbackToEdit','equipements'],
      data(){
          return{
            jour: '',
            zone: '',
            e: {},
            commentaire: '',
            code_machine:'',
          }
        },
        watch:{
          feedbackToEdit(newVal){
            this.jour = newVal.jour;
            this.zone = newVal.zone;
            this.commentaire = newVal.commentaire;
            this.code_machine = newVal.code_equip
          },
          equipements(newVal){
           this.e = newVal;
          }
        },
       validations: {
      jour: {
        required,
      },
      zone: {
        required,
      },
      code_machine: {
        required,
      },
      commentaire: {
        required,
      },
    
    },
    methods: {
    update(){
      axios.patch('/feedbacks/edit/' + this.feedbackToEdit.id, {
          jour: this.jour,
          zone: this.zone,
          code_equip: this.code_machine,
          commentaire: this.commentaire,
      })
      .then(response => this.$emit('feedback-updated',response))
      .catch(error => console.log(error));
    },
    getEquipements(zone){
      this.code_machine = '';
      axios.get('/historiques/equipement/zone/' + zone)
      .then(response =>this.e =  response.data)
      .catch(error => console.log(error));
    },
    checkEditForm(){
      this.$v.$touch()
      if(!this.$v.$invalid){
        var submitForm = document.getElementById('submitEditFeedback');
        submitForm.click();
      }else{
        alert("Veuillez remplir les champs correctement !");
      }
    },
  }
}
</script>