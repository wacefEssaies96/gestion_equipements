<template>
     <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Modifier un equipement</h5>
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
                    <EditFile @file-added="setFile" v-bind:typeDocument="typeDocument"></EditFile>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form @submit.prevent="checkEditForm">
                        <div class="card-body">
                                <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                            <label for="code">Code Machine</label>
                                            <input type="text" class="form-control" placeholder="Code Machine" v-model="code"
                                            :class="{'is-invalid':(code != '') ? $v.code.$invalid:'', 'is-valid':!$v.code.$invalid}">
                                            <div class="valid-feedback">Code Machine valide</div>
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
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="n_serie">N°serie</label>
                                        <input type="text" class="form-control" placeholder="n_serie" v-model="n_serie"
                                        :class="{'is-invalid':(n_serie != '') ? $v.n_serie.$invalid:'', 'is-valid':!$v.n_serie.$invalid}">
                                        <div class="valid-feedback">N°serie valide</div>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.n_serie.required">Veuillez entrer un N°serie valide !</span> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="emplacement">Emplacement</label>
                                            <input type="text" class="form-control" placeholder="Emplacement" v-model="emplacement" 
                                                :class="{'is-invalid':(emplacement != '') ? $v.emplacement.$invalid:'','is-valid':!$v.emplacement.$invalid}"
                                            >
                                            <div class="valid-feedback">emplacement valide</div>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.emplacement.required">Veuillez entrer un emplacement !</span>
                                                <span v-if="!$v.emplacement.minLength">Veuillez entrer au moins 3 caractères !</span>
                                                <span v-if="!$v.emplacement.maxLength">Maximum 15 caractères !</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="code_categorie">Code catégorie</label>
                                        <input type="text" class="form-control" placeholder="Code catégorie" v-model="code_categorie"
                                        :class="{'is-invalid':(code_categorie != '') ? $v.code_categorie.$invalid:'', 'is-valid':!$v.code_categorie.$invalid}">
                                        <div class="valid-feedback">N°serie valide</div>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.code_categorie.required">Veuillez entrer un N°serie valide !</span> 
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="date_acq">Date d'acquisition</label>
                                        <input type="date" class="form-control" v-model="date_acq"
                                        :class="{'is-invalid':(date_acq != '') ? $v.date_acq.$invalid:'', 'is-valid':!$v.date_acq.$invalid}">
                                        <div class="valid-feedback">date valide</div>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.date_acq.required">Veuillez entrer une date !</span> 
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label for="constructeur">Constructeur</label>
                                        <input type="text" class="form-control" placeholder="Constructeur" v-model="constructeur"
                                        :class="{'is-invalid':(constructeur != '') ? $v.constructeur.$invalid:'', 'is-valid':!$v.constructeur.$invalid}">
                                        <div class="valid-feedback">constructeur valide</div>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.constructeur.required">Veuillez entrer un constructeur !</span> 
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="etat">Etat</label>
                                            <select class="form-control" v-model="etat" 
                                                :class="{'is-invalid':(etat != '') ? $v.etat.$invalid:'','is-valid':!$v.etat.$invalid}"
                                            >
                                                <option value="Production">Production</option>
                                                <option value="Zsav">Zsav</option>
                                                <option value="obsolete">obsolete</option>
                                            </select>
                                            <div class="valid-feedback">etat valide</div>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.etat.required">Veuillez entrer une etat !</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <img style="width:100px; height:100px" :src="image" alt="Image">
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
                                <label>Ajouter des documents via OneDrive</label>
                                    <template v-if="connected == false">
                                        <p class="text text-danger">Vous devez être connecté à OneDrive !</p>
                                    </template> 
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#EditFile"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('liste_pr')"
                                            >
                                            liste PR
                                            </a>

                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#EditFile"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('ins_p')"     
                                            >
                                            Ins.M.Préventive
                                            </a>

                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#EditFile"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('dossier_technique')"     
                                            >
                                            Dossier Technique
                                            </a>

                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#EditFile"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('ins_c')"    
                                            >
                                            Ins.M.1ér niveau
                                            </a>
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
                    <button hidden id="submitEditEqui" type="submit" class="btn btn-primary" data-dismiss="modal" @click="update"></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { required, minLength,maxLength} from 'vuelidate/lib/validators';
    import EditFile from '../OneDrive/EditFile.vue';
export default {
    components:{
        EditFile
    },
    props: ['equipementToEdit','isConnected'],

    data: function(){
        return{
            nom: '',
            code: '',
            designation: '',
            n_serie: '',
            connected : '',
            image: null,
            imageFile:null,
            zone: '',
            filePath:'',
            typeDocument:'',
            documents:[],
            emplacement: '',
            code_categorie:'',
            date_acq:'',
            constructeur:'',
            etat:''
        }
    },
    watch:{
        equipementToEdit(newVal){
            this.code = newVal.code;
            this.designation = newVal.designation;
            this.n_serie = newVal.n_serie;
            this.image = newVal.image;
            this.zone = newVal.zone;
            this.emplacement = newVal.emplacement;
            this.code_categorie = newVal.code_categorie;
            this.date_acq = newVal.date_acq;
            this.etat = newVal.etat;
            this.constructeur = newVal.constructeur;

        },
        isConnected(newVal){
            this.connected = newVal;
        }
    },
    methods: {
        setType(type){
            this.typeDocument = type; 
        },
        setFile(response){
            this.filePath = response;
            this.setDocument();
        },
        setDocument(){
            this.filePath = this.filePath.split('public/')[1];
            let object = new Object();
            object.filePath = this.filePath;
            object.type = this.typeDocument;
            this.documents.push(object);
        },
        GetImage(e) {
            let img = e.target.files[0]
            let reader = new FileReader();
            this.imageFile = e.img;
            reader.readAsDataURL(img);
            reader.onload = e => {
                this.image = e.target.result
            }
        },
        update(){
            var json_arr = JSON.stringify(this.documents);
            console.log(this.documents);
            axios.patch('/equipements/edit/' + this.equipementToEdit.id, {
                nom: this.nom,
                code: this.code,
                designation: this.designation,
                n_serie: this.n_serie,
                image: this.image,
                zone: this.zone,
                emplacement: this.emplacement,
                code_categorie:this.code_categorie,
                date_acq:this.date_acq,
                constructeur:this.constructeur,
                etat:this.etat,
                documents: json_arr
            })
            .then(response => {
                this.$emit('equipement-updated',response)
                })
            .catch(error => console.log(error));
            this.documents = [];
        },
        checkEditForm(){
            this.$v.$touch()
            if(!this.$v.$invalid){
                var submitForm = document.getElementById('submitEditEqui');
                submitForm.click();
            }else{
                alert("Veuillez remplir les champs correctement !");
            }
        },
    },
    validations: {
        emplacement: {
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
        image: {
        },
        zone: {
            required
        },
        code_categorie:{
            required
        },
        date_acq:{
            required
        },
        constructeur:{
            required
        },
        etat:{
            required
        }
    },
}
</script>