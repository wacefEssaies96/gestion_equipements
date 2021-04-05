<template>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>One drive</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><router-link to="/">Dashboard</router-link></li>
                <li class="breadcrumb-item active">One drive</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="card m-lg-4">
        <template v-if="accessData.userName == null">
          <div class="card-header">
            <a href="/signin" class="btn btn-primary btn-large">Click ici pour se connecter à OneDrive</a>
          </div>
        </template>
        <template v-if="accessData.userName != null">
          <div class="card-header">
            <h4>Bienvenue  {{ accessData.userName }} !</h4>
            <a href="/signout" class="btn btn-primary btn-large">Se déconnecter de OneDrive</a>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-sm-3" v-for="file in files" :key="file.id">
                  <div class="card">
                  <span class="mailbox-attachment-icon">
                    <template v-if="file.folder"><i class="far fa-folder"></i></template>
                    <template v-if="file.file"><i class="far fa-file"></i></template>
                  </span>
                  
                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name">{{ file.name }}</a>
                      <span class="mailbox-attachment-size clearfix mt-1">
                        <span></span>
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
        </template>
      </div>
  </div>
</template>
<script>
export default {
    data(){
      return{
        files: '',
        file:'',
        accessData: '',
      }
    },
    created(){
      axios.get("/getAccessData")
      .then(response => this.accessData = JSON.parse(response.request.response))
      .catch(error => console.log(error))
      
      setTimeout(()=>{
        if(this.accessData.userName != null){
          axios.get("/getalldata")
          .then(response => this.files = response.data)
          .catch(error => console.log(error))
        }
      },1000);
    },
    methods:{
      getDataById(id){
        axios.get("/getdatabyid/"+id)
        .then(response => this.files = response.data)
        .catch(error => console.log(error))
      },
      downloadData(id){
        axios.get("/download/data/"+id)
        .then(response => this.file = response.data)
        .catch(error => console.log(error))
        this.equipementStore();
      },
      equipementStore(){
        axios.post('/importDataFromExcel',{
            file : this.file
        })
        .then(response => this.checkStatus(response.data))
        .catch(error => console.log(error));            
      },
      checkStatus(status){
        if(status == 'c bon'){
          this.toast('success', 'Les données ont été mis dans la base de données avec succées !');
          this.$router.push('/equipements');
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