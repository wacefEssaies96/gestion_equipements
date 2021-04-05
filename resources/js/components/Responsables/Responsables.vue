<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Responsables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Responsables</li>
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
                  <label>Nom</label>
                  <input @keyup="search" type="text" v-model="qNom" class="form-control" placeholder="Nom">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label>Prenom</label>
                  <input @keyup="search" type="text" v-model="qPrenom" class="form-control" placeholder="Prenom">
                </div>
              </div>
            
              <div class="col">
                <div class="from-group">
                  <label>Tel</label>
                  <input @keyup="search" type="text" v-model="qTel" class="form-control" placeholder="12345678">
                </div>
              </div>
              <div class="col">
                <div class="from-group">
                  <label>Email</label>
                  <input @keyup="search" type="text" v-model="qEmail" class="form-control" placeholder="Email">
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les responsables</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau responsable
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
    <!-- /.card-header -->
      <AddResponsable @responsable-added="refresh"></AddResponsable> 
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="responsable in responsables.data" :key="responsable.id">
              <td>{{ responsable.nom }}</td>
              <td>{{ responsable.prenom }}</td>
              <td>{{ responsable.email }}</td>
              <td>{{ responsable.tel }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getResponsable(responsable.id)">
                <i class="fas fa-edit" title="Modifier"/>
                </button>
                <button @click="setId(responsable.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger"><i class="fas fa-trash-alt" title="Supprimer"/></button>
                <DeleteResponsable v-bind:id="id" @responsable-deleted="refreshDeleted"></DeleteResponsable>
                <EditResponsable v-bind:responsableToEdit="responsableToEdit" @responsable-updated="refreshEdited"></EditResponsable>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="responsables" @pagination-change-page="getResults"></pagination>
      </div>
    <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddResponsable from './AddResponsable';
  import EditResponsable from './EditResponsable';
  import DeleteResponsable from './DeleteResponsable';
    export default {    
      components:{
      AddResponsable,
      EditResponsable,
      DeleteResponsable
    },
      
      props:['user'],
      data(){
        return{
          responsables: {},
          responsableToEdit: '',
          id:'',
          q: new FormData(),
          qNom:'',
          qPrenom:'',
          qTel:'',
          qEmail:'',
          hidden:'true',
          loading:true,
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.get("/responsables/liste")
        .then(response => {
          this.responsables = response.data;
          this.loading = false;
        })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.get('/responsables/liste?page=' + page)
          .then(response => {
            this.responsables = response.data;
          })
          .catch(error => console.log(error));
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
          this.q.append('role', 'RESPONSABLE');
          this.q.append('nom', this.qNom);
          this.q.append('prenom', this.qPrenom);
          this.q.append('tel', this.qTel);
          this.q.append('email', this.qEmail);

          axios.post("http://localhost:8000/users/search", this.q)
          .then(response => this.responsables = response.data)
          .catch(error => console.log(error))

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
        refreshDeleted(responsables){
          this.responsables = responsables.data;
          var value = 'Responsable a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(responsables){
          var value = 'Responsable a été modifié avec succées';
          this.responsables = responsables.data;
          this.toast(value);
        },
        refresh(responsables){
          var value = 'Responsable a été ajouté avec succées';
          this.responsables = responsables.data;
          this.toast(value);
        },
        getResponsable(id){
          axios.get('/responsables/edit/' + id)
          .then(response => this.responsableToEdit = response.data)
          .catch(error => console.log(error));
        },
        
      },
    }
</script>