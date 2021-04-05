<template>
     <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Modifier un code panne</h5>
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
                        <div class="form-group">
                            <label for="code">Code panne</label>
                            <input type="text" class="form-control" placeholder="Code Panne" v-model="code"
                            :class="{'is-invalid':$v.code.$invalid, 'is-valid':!$v.code.$invalid}">
                            <div class="valid-feedback">Code Panne valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.code.required">Veuillez entrer un code !</span> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" class="form-control" placeholder="Designation" v-model="designation"
                            :class="{'is-invalid':$v.designation.$invalid, 'is-valid':!$v.designation.$invalid}">
                            <div class="valid-feedback">Designation valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.designation.required">Veuillez entrer un designation !</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="zone">Zone</label>
                            <select class="form-control" v-model="zone"
                            :class="{'is-invalid':$v.zone.$invalid, 'is-valid':!$v.zone.$invalid}">
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
                    <button hidden id="submitEditCodep" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update"></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required} from 'vuelidate/lib/validators';
    export default {
      props: ['codePanneToEdit'],
      data: function(){
            return{
                code: '',
                designation: '',
                zone: '',
            }
        },
        watch:{
         codePanneToEdit(newVal){
            this.code = newVal.code;
            this.designation = newVal.designation;
            this.zone = newVal.zone;
          }
        },
        methods: {
          update(){
              axios.patch('/code_pannes/edit/' + this.codePanneToEdit.id, {
                  code: this.code,
                  designation: this.designation,
                  zone: this.zone
              })
              .then(response => this.$emit('codePanne-updated',response))
              .catch(error => console.log(error));
          },
          checkEditForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
              var submitForm = document.getElementById('submitEditCodep');
              submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
          },
      },
      validations: {
          code: {
            required,
          },
          designation: {
            required,
          },
          zone: {
              required
          },
        },
    }
</script>