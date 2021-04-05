<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau equipement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="deleteData">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Formulaire</h3>
                            </div>
                            <AddFile @file-added="setFile" v-bind:typeDocument="typeDocument"></AddFile>
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
                                            <label for="code">Code Machine</label>
                                            <input type="text" class="form-control" placeholder="Code Machine" v-model="code"
                                            :class="{'is-invalid':(code != '') ? $v.code.$invalid:'', 'is-valid':!$v.code.$invalid}">
                                            <div class="valid-feedback">Code Machine valide</div>
                                            <div class="invalid-feedback">
                                                <span v-if="!$v.code.required">Veuillez entrer un code !</span>
                                                <span v-if="!$v.code.isUnique">Code déja existant!</span> 
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
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
                                            <label for="n_serie">N°serie</label>
                                            <input type="text" class="form-control" placeholder="n_serie" v-model="n_serie"
                                            :class="{'is-invalid':(n_serie != '') ? $v.n_serie.$invalid:'', 'is-valid':!$v.n_serie.$invalid}">
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
                                                <img style="width:100px; height:100px" :src="image" alt="Image">
                                                <input type="file" class="form-control" placeholder="iamge" @change="GetImage">
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
                                    <label>Ajouter des documents via OneDrive</label>
                                    <template v-if="connected == false">
                                        <p class="text text-danger">Vous devez être connecté à OneDrive !</p>
                                    </template> 
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#addInstructionsEquip"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('liste_pr')"
                                            >
                                            liste PR
                                            </a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#addInstructionsEquip"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('ins_p')"     
                                            >
                                            Ins.M.Préventive
                                            </a>

                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#addInstructionsEquip"
                                            :class="{'disabled' : !connected}"
                                            @click="setType('dossier_technique')"     
                                            >
                                            Dossier Technique
                                            </a>

                                        </div>
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-outline-info" 
                                            data-toggle="modal" 
                                            data-target="#addInstructionsEquip"
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
                        <button type="button" class="btn btn-secondary" @click="deleteData" data-dismiss="modal">Fermer</button>
                        <button hidden type="submit" id="submitAddEqui" class="btn btn-primary" @click="equipementStore" data-dismiss="modal"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { required, minLength,maxLength } from 'vuelidate/lib/validators';
import AddFile from '../OneDrive/AddFile';

export default {
    components:{
        AddFile
    },
    props:['isConnected'],
    data(){
        return{
            nom: '',
            code: '',
            designation: '',
            n_serie: '',
            image:null,
            imageFile: null,
            zone: '',
            connected : '',
            filePath:'',
            typeDocument:'',
            documents:[],
        }
    },
    watch:{
        isConnected(newVal){
            this.connected = newVal;
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
            async isUnique(value){
                if(value==='') return true;
                const response = await axios.get('http://localhost:8000/equipements/'+value)
                .catch(error => console.log(error));
                if(response.data.length == 0) return true;
            }
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
        
    },
    methods:{
        deleteData(){
            if(this.documents.length > 0){
                var json_arr = JSON.stringify(this.documents);
                axios.post('/equipements/doc/delete',{
                    documents:json_arr
                })
                .then(response => console.log(response.data))
                .catch(error => console.log(error));
                this.documents = [];
            }else{
                console.log(this.documents);
            }
        },
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
            var json_arr = JSON.stringify(this.documents);
            let form = new FormData();
            form.append('nom', this.nom);
            form.append('code', this.code);
            form.append('designation', this.designation);
            form.append('n_serie', this.n_serie);
            form.append('image', this.image);
            form.append('zone', this.zone);
            form.append('documents', json_arr);
            const config= {headers:{'Content-Type': 'multipart/form-data'}};
            axios.post('/equipements',form ,config)
            .then(response => this.$emit('equipement-added',response))
            .catch(error => console.log(error));
            this.refreshData();
        },
        refreshData(){
            this.nom = '';
            this.code = '';
            this.designation = '';
            this.n_serie= '';
            this.image= '';
            this.imageFile= '';
            this.zone= '';
        }
    }
}
</script>
