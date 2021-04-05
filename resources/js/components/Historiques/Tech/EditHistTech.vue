<template>
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Modifier un historique</h5>
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
                                    <label for="heure_fin">Heure de fin de travaille</label>
                                    <input type="time" class="form-control" placeholder="Heure de fin de travaille" v-model="heure_fin" 
                                    :class="{'is-invalid':$v.heure_fin.$invalid,'is-valid':!$v.heure_fin.$invalid}"
                                    >
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.heure_fin.required">Veuillez remplir ce champs !</span>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label for="piece_rechange">Pièce de rechange</label>
                                <input type="text" class="form-control" placeholder="Pièce de rechange" v-model="piece_rechange"
                                :class="{'is-invalid':$v.piece_rechange.$invalid, 'is-valid':!$v.piece_rechange.$invalid}">
                                <div class="valid-feedback">Validé</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.piece_rechange.required">Veuillez remplir ce champs !</span>
                                </div>
                            </div>
                                    
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="code_machine">Code machine</label>
                                    <select class="form-control" v-model="code_machine"
                                    :class="{'is-invalid':$v.code_machine.$invalid, 'is-valid':!$v.code_machine.$invalid}">
                                        <option v-for="equipement in e" :key="equipement.id" :value="equipement.id">
                                            {{equipement.code}}
                                        </option>
                                    </select>
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.code_machine.required">Veuillez choisir une option !</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="code_panne">Code panne</label>
                                    <v-select v-model="code_panne" :multiple="true" 
                                        :options="options" 
                                        :get-option-label='option => option.code'
                                        :reduce='option => option.id'
                                        :class="{'is-invalid':$v.code_panne.$invalid, 'is-valid':!$v.code_panne.$invalid}"
                                        ></v-select>
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.code_panne.required">Veuillez choisir une option !</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="travaille">Travaille effectué</label>
                            <textarea class="form-control" placeholder="Travaille éffectué" v-model="travaille"
                            :class="{'is-invalid':$v.travaille.$invalid, 'is-valid':!$v.travaille.$invalid}"></textarea>
                            <div class="valid-feedback">Validé</div>
                            <div class="invalid-feedback">
                            <span v-if="!$v.travaille.required">Veuillez remplir ce champs !</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <template v-if="id.zone == 'Assemblage'">
                                <label for="num_planche">Num Planche</label>
                                <input type="text" class="form-control" v-model="num_planche"
                                :class="{'is-invalid':$v.num_planche.$invalid, 'is-valid':!$v.num_planche.$invalid}">
                                <div class="valid-feedback">Validé</div>
                                <div class="invalid-feedback">
                                <span v-if="!$v.num_planche.required">Veuillez remplir ce champs !</span>
                                </div>
                            </template>
                            <template v-if="id.zone == 'Sertissage'">
                                <label for="type_travaille">Type de travaille</label>
                                    <select v-model="type_travaille" class="form-control"
                                    :class="{'is-invalid':$v.type_travaille.$invalid, 'is-valid':!$v.type_travaille.$invalid}">
                                        <option value="">Choisir le type de travaille</option>
                                        <option value="C">C</option>
                                        <option value="P">P</option>
                                        <option value="PH">PH</option>
                                        <option value="S">S</option>
                                    </select>
                                <div class="valid-feedback">Validé</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.type_travaille.required">Veuillez remplir ce champs !</span>
                                </div>
                               
                            </template>
                            <template v-if="id.zone == 'Electrique'">
                                <label for="nom_support">Nom Support</label>
                                <input type="text" class="form-control" v-model="nom_support"
                                :class="{'is-invalid':$v.nom_support.$invalid, 'is-valid':!$v.nom_support.$invalid}">
                                <div class="valid-feedback">Validé</div>
                                <div class="invalid-feedback">
                                <span v-if="!$v.nom_support.required">Veuillez remplir ce champs !</span>
                                </div>
                            </template>
                        </div>
                        <div class="form-group">
                            <label for="commentaire">Commentaire</label>
                            <textarea class="form-control" v-model="commentaire">
                            </textarea>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button hidden id="submitEditHistorique" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update">Confirm</button>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { required } from 'vuelidate/lib/validators';
    import 'vue-select/dist/vue-select.css';
    export default {
        props: ['equipements','codePannes','id'],
        data(){
        return{
            e: {},
            c:[],
            heure_fin: '',
            travaille: '',
            commentaire: '',
            piece_rechange: '',
            code_machine: '',
            code_panne: '',
            num_planche: '',
            options:[],
            type_travaille: '',
            nom_support:'',
            hist: this.id,
            baseUrl:process.env.MIX_URL,

        }
    },
    watch:{
        equipements(newVal){
            this.e = newVal;
        },
        codePannes(newVal){
            this.c = newVal;
            this.options = this.c;
        },
    },
    methods: {
      update(){
        axios.patch(this.baseUrl+'/histtech/edit/' + this.hist.id, {
            heure_fin: this.heure_fin,
            travaille: this.travaille,
            piece_rechange: this.piece_rechange,
            commentaire: this.commentaire,
            code_panne: this.code_panne,
            code_equip: this.code_machine,
            num_planche: this.num_planche,
            type_travaille: this.type_travaille,
            nom_support: this.nom_support
        })
        .then(response => this.$emit('historique-updated',response))
        .catch(error => console.log(error));
      },
      checkEditForm(){
        this.$v.$touch()
        if(!this.$v.$invalid){
          var submitForm = document.getElementById('submitEditHistorique');
          submitForm.click();
        }
        else{
          alert("Veuillez remplir les champs correctement !");
        }
      },
    },
    validations: {
        heure_fin:{
            required
        },
        travaille: {
            required
        },
        commentaire: {
            required
        },
        piece_rechange: {
            required
        },
        code_machine: {
            required
        },
        code_panne: {
            required
        },
        num_planche: {
           
        },
        type_travaille:{
            
        },
        nom_support:{
           
        }
    },
  }
</script>