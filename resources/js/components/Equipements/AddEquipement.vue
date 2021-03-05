<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Ajouter un nouveau equipement
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau equipement</h5>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" class="form-control" placeholder="Nom" v-model="nom" 
                                    :class="{'is-invalid':$v.nom.$invalid,'is-valid':!$v.nom.$invalid}"
                                >
                                <div class="valid-feedback">Nom valide</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.nom.required">Veuillez entrer un nom !</span>
                                    <span v-if="!$v.nom.minLength">Veuillez entrer au moins 3 caractères !</span>
                                    <span v-if="!$v.nom.maxLength">Maximum 15 caractères !</span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="code">Code Machine</label>
                            <input type="text" class="form-control" placeholder="Code Machine" v-model="code"
                            :class="{'is-invalid':$v.code.$invalid, 'is-valid':!$v.code.$invalid}">
                            <div class="valid-feedback">Code Machine valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.code.required">Veuillez entrer un code !</span>
                                
                            </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
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
                            <label for="n_serie">N°serie</label>
                            <input type="text" class="form-control" placeholder="n_serie" v-model="n_serie"
                            :class="{'is-invalid':$v.n_serie.$invalid, 'is-valid':!$v.n_serie.$invalid}">
                            <div class="valid-feedback">N°serie valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.n_serie.required">Veuillez entrer un N°serie valide !</span> 
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="form-group">
                                    <img :src="avatar" alt="Image">
                                    <input type="file" class="form-control" placeholder="iamge" @change="GetImage">
            
                                </div>
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
                        <button hidden type="submit" id="submitAddEqui" class="btn btn-primary" @click="equipementStore" data-dismiss="modal"></button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required, minLength,maxLength } from 'vuelidate/lib/validators';
  export default {
         data(){
            return{
                nom: '',
                code: '',
                designation: '',
                n_serie: '',
                avatar:null,
                zone: '',
            }
        },
        validations: {
          nom: {
            required,
            minLength: minLength('3'),
            maxLength: maxLength('15')
          },
          code: {
            required,
           
          },
          designation: {
            required,
           
          },
          n_serie:{
            required,

          },
        

          zone: {
              required
          },
          
        },
        methods:{
          GetImage(e) {
            //   this.GetImage=e.traget.files[0]
            let image = e.traget.files[0]
            let reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                // console.log(e)
                this.avatar = e.target.result
            }
            
          },
          checkAddForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
              var submitForm = document.getElementById('submitAddEqui');
              submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
          },
          equipementStore(){
            
              axios.post('http://localhost:8000/equipements',{
                  nom: this.nom,
                  code: this.code,
                  designation: this.designation,
                  n_serie: this.n_serie,
                  image: this.image,               
                  zone: this.zone,
                  
              })
              .then(response => this.$emit('equipement-added',response))
              .catch(error => console.log(error));
          }
        }
  }
</script>
