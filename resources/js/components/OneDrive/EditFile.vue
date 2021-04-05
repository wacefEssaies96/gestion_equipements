<template>
    <!-- Modal -->
  <div class="modal fade" id="EditFile" tabindex="-1" role="dialog" aria-labelledby="EditFileTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="EditFileTitle">Ajouter un document vie Onedrive</h5>
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
            <button id="closeEdit" type="button" class="btn btn-secondary" data-dismiss='modal' @click="closeModal">Fermer</button>
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
        baseUrl:process.env.MIX_URL,
      }
    },
    watch:{
      typeDocument(newVal){
        this.type = newVal;
      }
    },  
    async created(){
     await axios.get(this.baseUrl+"/getAccessData")
      .then(response => this.accessData = JSON.parse(response.request.response))
      .catch(error => console.log(error))
      
      if(this.accessData.userName != null){
        await axios.get(this.baseUrl+"/getalldata")
        .then(response => this.files = response.data)
        .catch(error => console.log(error)) 
      }

    },
    methods:{
      closeModal(){
        $('#EditFile').on('click', '[data-dismiss="modal"]', function(e) { e.stopPropagation(); });
      },
      goBack(){
        if (this.counter == 1){
          axios.get(this.baseUrl+"/getalldata")
          .then(response => this.files = response.data)
          .catch(error => console.log(error))
          this.previous = [];
          this.counter = 0;
        }
        if(this.counter > 1){
          axios.get(this.baseUrl+"/getdatabyid/"+this.previous[this.previous.length - this.counter])
          .then(response => this.files = response.data)
          .catch(error => console.log(error))
          this.previous.pop();
          this.counter--;
        }
      },
      getDataById(id){
        this.counter++;
        this.previous.push(id);
        axios.get(this.baseUrl+"/getdatabyid/"+id)
        .then(response => this.files = response.data)
        .catch(error => console.log(error))
      },
      downloadData(id){
        axios.get(this.baseUrl+"/download/file/"+id)
        .then(response => {
          this.$emit('file-added',response.data);
          document.getElementById('closeEdit').click();
        })
        .catch(error => console.log(error))
      },

      checkStatus(status){
        if(status == 'c bon'){
          this.toast('success', 'Les données ont été mis dans la base de données avec succées !');
        }
        if(status == 'erreur'){
          this.toast('error', "Une erreur s'est produite !");
        }
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