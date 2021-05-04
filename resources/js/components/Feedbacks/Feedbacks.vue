<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Feedbacks</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/">Acceuil</router-link></li>
              <li class="breadcrumb-item active">Feedbacks</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="card m-lg-4">
        <template v-if="hidden == 'false'">
          <div class="card m-lg-4">
            <div class="card-header">
              <h3 class="card-title">Recherche</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Jour</label>
                    <input @keyup="search" type="date" v-model="qJour" class="form-control" placeholder="Jour">
                  </div>
                </div>
               <div class="col">
                <div class="from-group">
                  <label>Zone</label>
                  <select v-model="qZone" @change="search" class="form-control">
                    <option value="">Vide</option>
                  </select>
                </div>
              </div>

              <div class="col">
                <div class="form-group">
                  <label>Equipement</label>
                  <input @keyup="search" type="text" v-model="qEquipement" class="form-control" placeholder="Equipement">
                </div>
              </div>
               
                <div class="col">
                <div class="form-group">
                  <label>Commentaire</label>
                  <input @keyup="search" type="textArea" v-model="qCommentaire" class="form-control" placeholder="Commentaire">
                </div>
              </div>
                
              </div>
            </div>
          </div>
        </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les feedbacks</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
            <i class="fas fa-comment-medical" title=" Ajouter un feedback"></i>
         
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
      <AddFeedback @feedback-added="refreshAdded"></AddFeedback>  
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Jour</th>
              <th>Zone</th>
              <th>Equipement</th>
              <th>Commentaire</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="feedback in feedbacks.data" :key="feedback.id">
              <td>{{ feedback.jour}}</td>
              <td>{{ feedback.zone }}</td>
              <td>{{ feedback.designation }}</td>
              <td>{{ feedback.commentaire }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getFeedback(feedback.id)">
                  <i class="fas fa-edit" title="Modifier"/>
                </button>
                <button @click="setId(id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger">
                  <i class="fas fa-trash-alt" title="Supprimer"/></button>
                <DeleteFeedback v-bind:id="feedback.id" @feedback-deleted="refreshDeleted"></DeleteFeedback>
                <EditFeedback
                 v-bind:feedbackToEdit="feedbackToEdit"
                 v-bind:equipements="equipements"
                 @feedback-updated="refreshEdited">
                </EditFeedback>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :limit="5"  :data="feedbacks" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddFeedback from './AddFeedback';
  import EditFeedback from './EditFeedback';
  import DeleteFeedback from './DeleteFeedback';
    export default {
       components:{
          AddFeedback,
          EditFeedback,
          DeleteFeedback
      },
      props:['user'],
      data(){
        return{
          feedbacks: {},
          feedbackToEdit: '',
          q: new FormData(),
          qJour:'',
          qZone:'',
          qEquipement:'',
          qCommentaire:'',
          hidden:'true',
          id:'',
          equipements:'',
          loading : true
        }
      },
      created(){
        axios.get("/feedbacks/liste")
        .then(response => {
          this.feedbacks = response.data
          this.loading = false;
          })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.get('/feedbacks/liste?page=' + page)
          .then(response => {
            this.feedbacks = response.data;
          })
          .catch(error => console.log(error));
        },
       async getFeedback(id){
          await axios.get('/feedbacks/edit/'+id)
          .then(response => {
            this.feedbackToEdit = response.data
          })
          .catch(error => console.log(error));
          await axios.get('/historiques/equipement/zone/'+this.feedbackToEdit.zone)
          .then(response =>{
            this.equipements = response.data;
            console.log(this.equipements);
          })
          .catch(error => console.log(error));
        },

        setId(id){
          this.id = id
        },
        toast(value){
          this.$swal.fire({
            icon: 'success',
            title: value,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
            });
        },
        refreshDeleted(feedbacks){
          this.feedbacks = feedbacks.data;
          var value = 'Feedback a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(feedbacks){
          var value = 'Feedbacka été modifié avec succées';
          this.feedbacks = feedbacks.data;
          this.toast(value);
        },
        refreshAdded(feedbacks){
          var value = 'Feedback a été ajouté avec succées';
          this.feedbacks = feedbacks.data;
          this.toast(value);
        },
        showSearch(){
          if(this.hidden == 'true'){
            this.hidden = 'false';
            return 1;
          }
          if(this.hidden == 'false'){
            this.hidden = 'true';
            return 1;
          }
        },
        search(){
        //   this.q.append('role', 'PRODUCTION');
          this.q.append('jour', this.qJour);
          this.q.append('zone', this.qZone);
          this.q.append('equipement', this.qEquipement);
          this.q.append('commentaire', this.qCommentaire);

        //   axios.post("/feedbacks/search", this.q)
        //   .then(response => this.feedbacks = response.data)
        //   .catch(error => console.log(error))

        },
        refresh(feedbacks){
          this.feedbacks = feedbacks.data; 
        },

      }
    }
</script>