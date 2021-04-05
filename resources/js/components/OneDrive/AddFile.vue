<template>
    <!-- Modal -->
  <div class="modal fade" id="addInstructionsEquip" tabindex="-1" role="dialog" aria-labelledby="addInstructionsEquipTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div :class="{'overlay':l}"><!--class="overlay" -->
          <i :class="{'fas fa-2x fa-sync fa-spin':l}"></i><!--class="fas fa-2x fa-sync fa-spin" -->
        </div>
      <div class="modal-header">
          <h5 class="modal-title" id="addInstructionsEquipTitle">Ajouter un document vie Onedrive</h5>
          <button type="button" class="close" data-dismiss='modal' @click="closeModal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
          <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">One Drive</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
          <div class="card-body">
            <a class="btn-sm btn-secondary mb-4" @click="goBack()">
              <i class="fas fa-backward"></i>
              Revenir en arrière</a>
            <template v-if="files.length == 0">
                <h3 class="mt-4">Ce dossier est vide !</h3>
            </template>
            <div class="row mt-4">
                <div class="col-sm-3" v-for="file in files" :key="file.id">
                  <div class="card">
                  <span class="mailbox-attachment-icon">
                    <template v-if="file.folder"><i class="far fa-folder"></i></template>
                    <template v-if="file.file"><i class="far fa-file"></i></template>
                  </span>
                  <div class="mailbox-attachment-info">
                    <a :href="file.webUrl" class="mailbox-attachment-name">{{ file.name }}</a>
                      <span class="mailbox-attachment-size clearfix mt-1">
                        <span>{{(file.size / 1024).toFixed(2)}} Ko</span>
                          <template v-if="file.folder">
                            <button @click="getDataById(file.id)" class="btn btn-default btn-sm float-right">
                                <i class="fas fa-folder"></i>
                              Ouvrir</button>
                          </template>
                          <template v-if="file.file">
                            <button @click="downloadData(file.id)" class="btn btn-default btn-sm float-right">
                              <i class="fas fa-cloud-download-alt"></i>
                            </button>
                          </template>
                      </span>
                  </div>
                  </div>
                </div> 
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
          </div>
      </div>
      <!-- /.card -->
      </div>
      <div class="modal-footer">
            <button id="close" type="button" class="btn btn-secondary" data-dismiss='modal' @click="closeModal">Fermer</button>
      </div>
      </div>
  </div>
  </div>
</template>
<script>
export default {
  props:['typeDocument'],
    data(){
      return{
        files: '',
        file:'',
        accessData: '',
        previous:[],
        counter:0,
        type: '',
        l:false,
      }
    },
    watch:{
      typeDocument(newVal){
        this.type = newVal;
      }
    },  
    async created(){
     await axios.get("/getAccessData")
      .then(response => this.accessData = JSON.parse(response.request.response))
      .catch(error => console.log(error))
      
      if(this.accessData.userName != null){
        await axios.get("/getalldata")
        .then(response => this.files = response.data)
        .catch(error => console.log(error)) 
      }

    },
    methods:{
      closeModal(){
        $('#addInstructionsEquip').on('click', '[data-dismiss="modal"]', function(e) { e.stopPropagation(); });
      },
      goBack(){
        this.l = true;
        if (this.counter == 1){
          axios.get("/getalldata")
          .then(response => {
            this.files = response.data  
            this.l = false;
          })
          .catch(error => console.log(error))
          this.previous = [];
          this.counter = 0;
        }
        if(this.counter > 1){
          axios.get("/getdatabyid/"+this.previous[this.previous.length - this.counter])
          .then(response => {
            this.files = response.data
            this.l = false;  
          })
          .catch(error => console.log(error))
          this.previous.pop();
          this.counter--;
        }
      },
      getDataById(id){
        this.l = true;
        this.counter++;
        this.previous.push(id);
        axios.get("/getdatabyid/"+id)
        .then(response => {
          this.files = response.data
          this.l = false
          })
        .catch(error => console.log(error))
      },
      downloadData(id){
        this.l = true;
        axios.get("/download/file/"+id)
        .then(response => {
          this.$emit('file-added',response.data);
          document.getElementById('close').click();
          this.l = false;
          this.toast('success', 'Le fichier a été mis avec succées !');
        })
        .catch(error => {
          console.log(error),
          this.toast('error', "Une erreur s'est produite !");
        })
      },
      toast(value,message){
        this.$swal.fire({
          icon: value,
          title: message,
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 5000
        });
      },
    }
}
</script>