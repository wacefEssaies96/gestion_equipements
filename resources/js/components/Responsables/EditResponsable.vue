<template>
     <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Modifier un responsable</h5>
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
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" class="form-control" placeholder="Nom" v-model="responsableToEdit.nom">
                  </div>
                  <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" id="prenom" class="form-control" placeholder="prenom" v-model="responsableToEdit.prenom">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="email" v-model="responsableToEdit.email">
                  </div>
                  <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="pseudo" id="pseudo" class="form-control" placeholder="pseudo" v-model="responsableToEdit.pseudo">
                  </div>
                  <div class="form-group">
                    <label for="tel">téléphone</label>
                    <input type="tel" id="tel" class="form-control" placeholder="tel" v-model="responsableToEdit.tel">
                  </div>
                  <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="password" class="form-control" placeholder="Mot de passe" v-model="password"
                    :class="{'is-invalid':$v.password.$invalid, 'is-valid':!$v.password.$invalid}" >
                    <div class="valid-feedback">Mot de passe valide</div>
                    <div class="invalid-feedback">
                      <span v-if="!$v.password.required">Veuillez entrer un mot de passe !</span>
                      <span v-if="!$v.password.minLength">Veuillez entrer au moins 6 caractères !</span>
                      <span v-if="!$v.password.maxLength">Maximum 15 caractères !</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="mdp">Répéter le mot de passe</label>
                    <input type="password" id="repeatPassword" class="form-control" placeholder="Répéter le mot de passe" v-model="repeatPassword"
                    :class="{'is-invalid':$v.password.$invalid, 'is-valid': (password != '') ? !$v.repeatPassword.$invalid : '' }" >
                    <div class="valid-feedback">Mot de passe identique !</div>
                    <div class="invalid-feedback">
                      <span v-if="!$v.repeatPassword.sameAsPassword">Les mots de passes doivent être identique !</span>
                    </div>
                  </div>
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-dismiss="modal" @click="update">Confirm</button>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
import { required, minLength,maxLength, between, sameAs } from 'vuelidate/lib/validators';
    export default {
      props: ['responsableToEdit'],
      data(){
            return{
                password: '',
                repeatPassword: '',
            }
        },
        validations: {
          password: {
            required,
            minLength: minLength(6),
            maxLength: maxLength(15),
          },
          repeatPassword: {
            sameAsPassword: sameAs('password')
          }
        },
         methods: {
            update(){
                axios.patch('http://localhost:8000/responsables/edit/' + this.responsableToEdit.id, {
                    nom: this.responsableToEdit.nom,
                    prenom: this.responsableToEdit.prenom,
                    tel: this.responsableToEdit.tel,
                    email: this.responsableToEdit.email,
                    pseudo: this.responsableToEdit.pseudo,
                    password: this.responsableToEdit.password,
                })
                .then(response => this.$emit('responsable-updated',response))
                .catch(error => console.log(error));
            }
        }
    }
</script>