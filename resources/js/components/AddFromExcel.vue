<template>
     <!-- Modal -->
        <div class="modal fade" id="addFromExcel" tabindex="-1" role="dialog" aria-labelledby="addFromExcelTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div :class="{'overlay':l}"><!--class="overlay" -->
                        <i :class="{'fas fa-2x fa-sync fa-spin':l}"></i><!--class="fas fa-2x fa-sync fa-spin" --></div>
                        <h5 class="modal-title" id="addFromExcelTitle">Ajouter des données via un fichier excel</h5>
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
                                <input type="file" class="from-control" @change="getFile">
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
                        <button type="submit" hidden id="closeModal" data-dismiss="modal"></button>
                        <button hidden type="submit" id="submitAddFile" class="btn btn-primary" @click="storeFile"></button>
                    </div>
                </div>   
            </div>
        </div>
</template>
<script>
export default {
    props:['type'],
    data(){
        return{
            file: null,
            l: false,
        }
    },

    methods:{
        checkAddForm(){
            document.getElementById('submitAddFile').click();
        },
        getFile(e) {
            this.file = e.target.files[0];
        },
        storeFile(){
            this.l = true;
            let form = new FormData();
            form.append('file', this.file);
            const config= {headers:{'Content-Type': 'multipart/form-data'}};
            if(this.type == "codePanne"){
                console.log(form.file);
                axios.post('/code-pannes/excel',form ,config)
                .then(response => {
                    this.$emit('codePanne-added',response)
                    this.l = false;     
                    document.getElementById('closeModal').click();
                })
                .catch(error => console.log(error));
            }
            if(this.type == "equipement"){
                axios.post('/equipements/excel',form ,config)
                .then(response => {
                    this.$emit('codePanne-added',response)
                    this.l = false; 
                    document.getElementById('closeModal').click();
                })
                .catch(error => console.log(error));
            }
            if(this.type == "historiques"){
                console.log(form);
                axios.post('/historiques/excel',form ,config)
                .then(response => {
                    this.$emit('historique-added-added',response)
                    this.l = false; 
                    document.getElementById('closeModal').click();
                })
                .catch(error => console.log(error));
            }
        }
        
    }
    
}
</script>