<template>
<div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau feedback</h5>
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
                        
                         <div class="form-group">
                              <label for="jour">Jour</label>
                              <input type="date" class="form-control" placeholder="Jour" v-model="jour"
                              :class="{'is-invalid':(jour != '') ?$v.jour.$invalid:'', 'is-valid':!$v.jour.$invalid}">
                              <div class="valid-feedback">Jour validé</div>
                              <div class="invalid-feedback">
                                <span v-if="!$v.jour.required">Veuillez remplir ce champs !</span>
                              </div>
                        
                             <div class="form-group">
                            <label for="zone">Zone</label>
                            <select class="form-control" v-model="zone" @change="getEquipements(zone)"
                            :class="{'is-invalid':(zone != '') ? $v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}">
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
                        
                      
                        <div class="form-group">
                            <label for="equipement">Equipement</label>
                            <select class="form-control" v-model="equipement"
                            :class="{'is-invalid':(equipement != '') ? $v.equipement.$invalid:'', 'is-valid':!$v.equipement.$invalid}">
                                <option v-for="equipement in e" :key="equipement.id" :value="equipement.id">
                                   
                                    {{equipement.nom}}
                                </option>
                            </select>
                            <div class="valid-feedback">Equipement validé</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.equipement.required">Veuillez choisir une equipement!</span>
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
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  <button hidden type="submit" id="submitAddFeedback" class="btn btn-primary" @click="feedbackStore" data-dismiss="modal"></button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
import { required} from 'vuelidate/lib/validators';
  export default {
    data(){
      return{
        jour: '',
        zone: '',
        equipement: '',
        commentaire: '',
        e:{},
        baseUrl:process.env.MIX_URL,
      }
    },
    validations: {
      jour: {
        required,
      },
      zone: {
        required,
      },
      
      equipement: {
        required,
      },
      commentaire: {
        required,
      },
    
    },
    methods:{
      checkAddForm(){
        this.$v.$touch()
        if(!this.$v.$invalid){
          var submitForm = document.getElementById('submitAddFeedback');
          submitForm.click();
        }else{
            alert("Veuillez remplir les champs correctement !");
        }
      },
      feedbackStore(){
        this.equipement = '';
        axios.post(this.baseUrl+'/feedbacks',{
            jour: this.jour,
            zone: this.zone,
            equipement: this.equipement,
            commentaire: this.commentaire,
           
        })
        .then(response => this.$emit('feedback-added',response)) 
        .catch(error => console.log(error));
        this.refreshData();
        
      },
      getEquipements(zone){
        this.equipement = '';
        axios.get(this.baseUrl+'/historiques/equipement/zone/' + zone)
        .then(response =>this.e =  response.data)
        .catch(error => console.log(error));
        
      },
      refreshData(){
        this.jour = '';
        this.zone = '';
        this.equipement = '';
        this.commentaire= '';
        
      },
      getEquipById(id){
        this.equipement = '';
        axios.get(this.baseUrl+'/equipements/equipement/nom/' + id)
        .then(response =>this.e =  response.data)
        .catch(error => console.log(error));
      }

    }
  }
</script>
