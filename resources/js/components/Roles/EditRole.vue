<template>
     <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Modifier role</h5>
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
                            <label for="designation">Designation</label>
                            <input type="text" class="form-control" placeholder="Designation" v-model="designation"
                            :class="{'is-invalid':$v.designation.$invalid, 'is-valid':!$v.designation.$invalid}">
                            <div class="valid-feedback">Designation valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.designation.required">Veuillez entrer un designation !</span>
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
                    <button hidden id="submitEditRole" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update"></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required} from 'vuelidate/lib/validators';
    export default {
      props: ['roleToEdit'],
      data: function(){
            return{
                designation: '',
            }
        },
        watch:{
         roleToEdit(newVal){
            this.designation = newVal.designation;
          }
        },
        methods: {
          update(){
              axios.patch('/roles/edit/' + this.roleToEdit.id, {
                  designation: this.designation,
              })
              .then(response => this.$emit('role-updated',response))
              .catch(error => console.log(error));
          },
          checkEditForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
              var submitForm = document.getElementById('submitEditRole');
              submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
          },
      },
      validations: {

          designation: {
            required,
          },
        
        },
    }
</script>