<template>
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Modifier un intervention</h5>
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
                                    <label for="code_panne">Code panne</label>
                                    <v-select v-model="code_panne" :multiple="true" 
                                        :options="options" 
                                        :get-option-label="option => option.code+' '+option.designation"
                                        :reduce='option => option.id'
                                        :class="{'is-invalid':(code_panne != '') ? $v.code_panne.$invalid:'', 'is-valid':!$v.code_panne.$invalid}"
                                        ></v-select>
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.code_panne.required">Veuillez choisir une option !</span>
                                    </div>
                                </div>
                                    
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="piece_rechange">Pièce de rechange</label>
                                    <input type="text" class="form-control" placeholder="Pièce de rechange" v-model="piece_rechange"
                                    :class="{'is-invalid':(piece_rechange != '') ? $v.piece_rechange.$invalid : '', 'is-valid':!$v.piece_rechange.$invalid}">
                                    <div class="valid-feedback">Validé</div>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.piece_rechange.required">Veuillez remplir ce champs !</span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <label for="documents">Documents des équipements</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="" @click="getDocuments(id.code_equip,'ins_c')" class="btn btn-outline-info">
                                <i class="fas fa-file-pdf"></i> Instruction 1ér niveau
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="" @click="getDocuments(id.code_equip,'ins_p')" class="btn btn-outline-info">
                                <i class="fas fa-file-pdf"></i> Instruction préventive
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="" @click="getDocuments(id.code_equip,'dossier_technique')" class="btn btn-outline-info">
                                <i class="fas fa-file-pdf"></i> Dossier technique
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="" @click="getDocuments(id.code_equip,'liste_pr')" class="btn btn-outline-info">
                                <i class="fas fa-file-pdf"></i> Liste PR
                                </a>
                            </div>
                        </div>
                        <ViewPdf v-bind:path="document.document"></ViewPdf>
                        <button hidden id="openPdf" data-toggle="modal" data-target="#viewpdf"></button>
                        <div class="form-group">
                            <label for="travaille">Travaille effectué</label>
                            <textarea class="form-control" placeholder="Travaille éffectué" v-model="travaille"
                            :class="{'is-invalid':(travaille != '') ? $v.travaille.$invalid : '', 'is-valid':!$v.travaille.$invalid}"></textarea>
                            <div class="valid-feedback">Validé</div>
                            <div class="invalid-feedback">
                                <span v-if="!$v.travaille.required">Veuillez remplir ce champs !</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <template v-if="id.zone == 'Assemblage'">
                                <label for="num_planche">Num Planche</label>
                                <input type="text" class="form-control" v-model="num_planche">
                            </template>
                            <template v-if="id.zone == 'Electrique'">
                                <label for="nom_support">Nom Support</label>
                                <input type="text" class="form-control" v-model="nom_support">
                                
                            </template>
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
    import ViewPdf from '../../Equipements/ViewPdfFile';

    export default {
        components:{
            ViewPdf
        },
        props: ['equipements','codePannes','id'],
        data(){
        return{
            e: {},
            c:[],
            travaille: '',
            commentaire: '',
            piece_rechange: '',
            code_panne: '',
            num_planche: '',
            options:[],
            nom_support:'',
            hist: this.id,
            document:''
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
        getDocuments(id,type){
        axios.get('/equipements/document/'+id+'/'+type)
        .then(response => {
          if(response.data != 'introuvable'){
            document.getElementById('openPdf').click();
          }else{
            this.$swal.fire({
              icon: 'warning',
              title: "Aucun document disponible, veuillez contactez l'administrateur !",
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5000
            });
          }
        })
        .catch(error => console.log(error))
      },
      update(){
        axios.patch('/histtech/edit/' + this.hist.id, {
            travaille: this.travaille,
            piece_rechange: this.piece_rechange,
            code_panne: this.code_panne,
            num_planche: this.num_planche,
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
        travaille: {
            required
        },
        piece_rechange: {
            required
        },
        code_panne: {
            required
        }
    },
  }
</script>