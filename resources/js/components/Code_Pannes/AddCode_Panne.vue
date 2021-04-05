<template>
    <div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau code panne</h5>
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
                                    <label for="code">Code Panne</label>
                                    <input type="text" class="form-control" placeholder="Code Panne" v-model="code"
                                        :class="{'is-invalid':(code != '') ? $v.code.$invalid:'', 'is-valid':!$v.code.$invalid}">
                                    <div class="valid-feedback">Code Panne valide</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.code.required">Veuillez entrer un code !</span>
                                        <span v-if="!$v.code.isUnique">Code déja existant!</span>   
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" placeholder="Designation" v-model="designation"
                                    :class="{'is-invalid':(designation != '') ? $v.designation.$invalid:'', 'is-valid':!$v.designation.$invalid}">
                                    <div class="valid-feedback">Designation valide</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.designation.required">Veuillez entrer un designation !</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="zone">Zone</label>
                                    <select class="form-control" v-model="zone"
                                    :class="{'is-invalid':(code != '') ?$v.zone.$invalid:'', 'is-valid':!$v.zone.$invalid}">
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
                        <button hidden type="submit" id="submitAddCodep" class="btn btn-primary" @click="codepanneStore" data-dismiss="modal"></button>
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
            code: '',
            designation: '',
            zone: '',
            baseUrl:process.env.MIX_URL,
        }
    },
    validations: {
        code: {
            required,
            async isUnique(value){
                if(value==='') return true;
                const response = await axios.get(this.baseUrl+'/code_pannes/'+value)
                .catch(error => console.log(error));
                if(response.data.length == 0) return true;
            }
        },
        designation: {
            required,
        },
        zone: {
            required
        },
    },
    methods:{
        checkAddForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
                var submitForm = document.getElementById('submitAddCodep');
                submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
        },
        codepanneStore(){
            let form = new FormData();
            form.append('code', this.code);
            form.append('designation', this.designation);
            form.append('zone', this.zone);
            axios.post(this.baseUrl+'/code_pannes',form)
            .then(response => this.$emit('codePanne-added',response))
            .catch(error => console.log(error));
            this.refreshData();
        },
        refreshData(){
            this.code = '';
            this.designation = '';
            this.zone = '';
        }
    }
  }
</script>
