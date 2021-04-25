<template>
     <!-- Modal -->
        <div class="modal fade" id="addFromExcel" tabindex="-1" role="dialog" aria-labelledby="addFromExcelTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
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
                        <button hidden type="submit" id="submitAddFile" class="btn btn-primary" @click="storeFile" data-dismiss="modal"></button>
                    </div>
                </div>   
            </div>
        </div>
</template>
<script>
export default {

    data(){
        return{
            file: null,
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
            let form = new FormData();
            form.append('file', this.file);
            const config= {headers:{'Content-Type': 'multipart/form-data'}};
            axios.post('/code-pannes/excel',form ,config)
            .then(response => this.$emit('codePanne-added',response))
            .catch(error => console.log(error));
        }
        
    }
    
}
</script>