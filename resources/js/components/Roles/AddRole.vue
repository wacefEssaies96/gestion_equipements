<template>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau Role</h5>
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
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" placeholder="Designation" v-model="designation"
                                    :class="{'is-invalid':(designation != '') ? $v.designation.$invalid:'', 'is-valid':!$v.designation.$invalid}">
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
                        <button hidden type="submit" id="submitAddRole" class="btn btn-primary" @click="roleStore" data-dismiss="modal"></button>
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
            designation: '',
        }
    },
    validations: {
       
        designation: {
            required,
        },
    },
    methods:{
        checkAddForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
                var submitForm = document.getElementById('submitAddRole');
                submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
        },
        roleStore(){
            let form = new FormData();
            form.append('designation', this.designation);
            axios.post('/roles',form)
            .then(response => this.$emit('role-added',response))
            .catch(error => console.log(error));
            this.refreshData();
        },
        refreshData(){
            this.designation = '';
        }
    }
  }
</script>
