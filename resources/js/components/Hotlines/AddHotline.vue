<template>
<div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Ajouter un nouveau hotline
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau hotline</h5>
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
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" placeholder="Prénom" v-model="prenom"
                        :class="{'is-invalid':$v.prenom.$invalid, 'is-valid':!$v.prenom.$invalid}">
                        <div class="valid-feedback">Prénom valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.prenom.required">Veuillez entrer un prénom !</span>
                          <span v-if="!$v.prenom.minLength">Veuillez entrer au moins 3 caractères !</span>
                          <span v-if="!$v.prenom.maxLength">Maximum 15 caractères !</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="tel">Téléphone</label>
                        <input type="text" class="form-control" placeholder="Numéro de téléphone" v-model="tel"
                        :class="{'is-invalid':$v.tel.$invalid, 'is-valid':!$v.tel.$invalid}">
                        <div class="valid-feedback">Numéro de téléphone valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.tel.required">Veuillez entrer un numéro de téléphone !</span>
                          <span v-if="!$v.tel.isUnique">Veuillez entrer seulement des chiffres !</span>
                          <span v-if="!$v.tel.minLength">Veuillez entrer 8 chiffre ! (********)</span>
                          <span v-if="!$v.tel.maxLength">Veuillez entrer 8 chiffre ! (********)</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="pseudo" class="form-control" placeholder="Pseudo" v-model="pseudo"
                        :class="{'is-invalid':$v.pseudo.$invalid, 'is-valid':!$v.pseudo.$invalid}">
                        <div class="valid-feedback">Pseudo valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.pseudo.required">Veuillez entrer un pseudo valide !</span>
                          <span v-if="!$v.pseudo.minLength">Veuillez entrer au moins 3 caractères !</span>
                          <span v-if="!$v.pseudo.maxLength">Maximum 15 caractères !</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Email" v-model="email"
                        :class="{'is-invalid':$v.email.$invalid, 'is-valid':!$v.email.$invalid}">
                        <div class="valid-feedback">Email valide</div>
                        <div class="invalid-feedback">
                          <span v-if="!$v.email.required">Veuillez entrer un email !</span>
                          <span v-if="!$v.email.isUnique">Veuillez entrer un email valide !</span>
                          <span v-if="!$v.email.isValid">Email déja existant !</span>
                        </div>
                      </div>
                  
                  <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" class="form-control" placeholder="Mot de passe" v-model="password"
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
                    <input type="password" class="form-control" placeholder="Répéter le mot de passe" v-model="repeatPassword"
                    :class="{'is-invalid':$v.repeatPassword.$invalid, 'is-valid': (password != '') ? !$v.repeatPassword.$invalid : '' }" >
                    <div class="valid-feedback">Mot de passe identique !</div>
                    <div class="invalid-feedback">
                      <span v-if="!$v.repeatPassword.sameAsPassword">Les mots de passes doivent être identique !</span>
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
                  <button hidden type="submit" id="submitAddHotline" class="btn btn-primary" @click="hotlineStore" data-dismiss="modal"></button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
import { required, minLength,maxLength, sameAs } from 'vuelidate/lib/validators';
  export default {
         data(){
            return{
                nom: '',
                prenom: '',
                email: '',
                tel: '',
                pseudo: '',
                password: '',
                repeatPassword: '',
            }
        },
        validations: {
          nom: {
            required,
            minLength: minLength('3'),
            maxLength: maxLength('15')
          },
          prenom: {
            required,
            minLength: minLength('3'),
            maxLength: maxLength('15')
          },
          pseudo: {
            required,
            minLength: minLength('3'),
            maxLength: maxLength('15')
          },
          email: {
            required,
            isUnique(value){
              if(value === '') return true;
              var email_regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return new Promise((resolve)=>{
                setTimeout(()=>{
                  resolve(email_regex.test(value))
                },350+Math.random()*300)
              })
            },
            async isValid(value){
                if(value==='') return true;
                const response = await axios.get('http://localhost:8000/responsables/verifemail/'+value)
                .catch(error => console.log(error));
                if(response.data.length == 0) return true;
            }
          },
          tel: {
            required,
            minLength: minLength('8'),
            maxLength: maxLength('8'),
            isUnique(value){
              if(value==='') return true;
              var tel_regex = /^[0-9]+$/;
              return new Promise((resolve)=>{
                setTimeout(()=>{
                  resolve(tel_regex.test(value))
                },350+Math.random()*300)
              })
            }
          },
          password: {
            required,
            minLength: minLength(6),
            maxLength: maxLength(15),
          },
          repeatPassword: {
            sameAsPassword: sameAs('password')
          }
        },
        methods:{
          checkAddForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
              var submitForm = document.getElementById('submitAddHotline');
              submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
          },
          hotlineStore(){
              axios.post('http://localhost:8000/hotlines',{
                  nom: this.nom,
                  prenom: this.prenom,
                  email: this.email,
                  tel: this.tel,
                  pseudo: this.pseudo,
                  password: this.password,
                  role : this.role
              })
              .then(response => this.$emit('hotline-added',response))//this.$emit('hotline-added',response)
              .catch(error => console.log(error));
          }
        }
  }
</script>
