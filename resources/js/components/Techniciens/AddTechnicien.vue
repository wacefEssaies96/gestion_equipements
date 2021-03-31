<template>
    <div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau technicien</h5>
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
                                    :class="{'is-invalid':(nom != '') ? $v.nom.$invalid:'','is-valid':!$v.nom.$invalid}"
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
                            :class="{'is-invalid':(prenom != '') ? $v.prenom.$invalid:'', 'is-valid':!$v.prenom.$invalid}">
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
                            :class="{'is-invalid':(tel != '') ?$v.tel.$invalid:'', 'is-valid':!$v.tel.$invalid}">
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
                            :class="{'is-invalid':(pseudo != '') ? $v.pseudo.$invalid:'', 'is-valid':!$v.pseudo.$invalid}">
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
                            :class="{'is-invalid':(email != '') ?$v.email.$invalid:'', 'is-valid':!$v.email.$invalid}">
                            <div class="valid-feedback">Email valide</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.email.required">Veuillez entrer un email !</span>
                                <span v-if="!$v.email.isUnique">Veuillez entrer un email valide !</span>
                                <span v-if="!$v.email.isValid">Email déja existant !</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <textarea id="qualification" class="form-control" placeholder="qualification" v-model="qualification"
                            :class="{'is-invalid':(qualification != '') ?$v.qualification.$invalid:'', 'is-valid':!$v.qualification.$invalid}"></textarea>
                            <div class="valid-feedback">Validé</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.qualification.required">Veuillez remplir ce champs !</span>
                                <span v-if="!$v.qualification.minLength">Veuillez entrer au moins 3 caractères !</span>
                                <span v-if="!$v.qualification.maxLength">Maximum 190 caractères !</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="h_debut_service">Heurs de début de services</label>
                                    <input type="time" class="form-control" placeholder="de 8h" v-model="h_debut_service"
                                    :class="{'is-invalid':(h_debut_service != '') ? $v.h_debut_service.$invalid:'', 'is-valid':!$v.h_debut_service.$invalid}">
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.h_debut_service.required">Veuillez remplir ce champs !</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="h_fin_service">Heurs de fin de services</label>
                                <input type="time" class="form-control" placeholder="jusqu'a 14h" v-model="h_fin_service"
                                :class="{'is-invalid': (h_fin_service != '') ? $v.h_fin_service.$invalid:'', 'is-valid':!$v.h_fin_service.$invalid}">
                                <div class="valid-feedback">Validé</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.h_fin_service.required">Veuillez remplir ce champs !</span>
                                </div>
                            </div>
                           </div>
                        </div>  
                        <div class="form-group">
                            <label for="zone">Zone</label>
                            <select class="form-control" v-model="zone"
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
                        <div class="form-group">
                            <label for="mdp">Mot de passe</label>
                            <input type="password" class="form-control" placeholder="Mot de passe" v-model="password"
                            :class="{'is-invalid': (password != '') ?$v.password.$invalid:'', 'is-valid':!$v.password.$invalid}" >
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
                        :class="{'is-valid': (password != '') ? !$v.repeatPassword.$invalid : '','is-invalid':$v.repeatPassword.$invalid}" >
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
                        <button hidden type="submit" id="submitAddTech" class="btn btn-primary" @click="technicienStore" data-dismiss="modal"></button>
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
            zone: '',
            pseudo: '',
            qualification: '',
            h_debut_service: '',
            h_fin_service: '',
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
        qualification:{
        required,
        minLength: minLength('3'),
        maxLength: maxLength('190')
        },
        zone: {
            required
        },
        h_debut_service: {
            required
        },
        h_fin_service: {
            required
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
                var submitForm = document.getElementById('submitAddTech');
                submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
        },
        technicienStore(){
            axios.post('http://localhost:8000/techniciens',{
                nom: this.nom,
                prenom: this.prenom,
                email: this.email,
                tel: this.tel,
                pseudo: this.pseudo,
                password: this.password,
                h_debut_service: this.h_debut_service,
                h_fin_service: this.h_fin_service,
                zone: this.zone,
                qualification: this.qualification
            })
            .then(response => this.$emit('technicien-added',response))
            .catch(error => console.log(error));
            this.refreshData();
        },
        refreshData(){
            this.nom = '';
            this.prenom = '';
            this.email = '';
            this.tel= '';
            this.pseudo= '';
            this.password= '';
            this.repeatPassword= '';
            this.h_debut_service = '';
            this.h_fin_service = '';
            this.zone = '';
            this.qualification = '';
        }
    }
  }
</script>
