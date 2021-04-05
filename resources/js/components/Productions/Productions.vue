<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Productions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Productions</li>
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
        <h3 class="card-title">Liste de tous les productions</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau production
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
      <AddProduction @production-added="refreshAdded"></AddProduction>  
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
            <tr v-for="production in productions.data" :key="production.id">
              <td>{{ production.nom }}</td>
              <td>{{ production.prenom }}</td>
              <td>{{ production.email }}</td>
              <td>{{ production.tel }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getProduction(production.id)">
                  <i class="fas fa-edit" title="Modifier"/>
                </button>
                <button @click="setId(production.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger">
                  <i class="fas fa-trash-alt" title="Supprimer"/>
                </button>
                <DeleteProduction v-bind:id="id" @production-deleted="refreshDeleted"></DeleteProduction>
                <EditProduction v-bind:productionToEdit="productionToEdit" @production-updated="refreshEdited"></EditProduction>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="productions" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddProduction from './AddProduction';
  import EditProduction from './EditProduction';
  import DeleteProduction from './DeleteProduction';

    export default {
       components:{
          AddProduction,
          EditProduction,
          DeleteProduction
      },
      props:['user'],
      data(){
        return{
          productions: {},
          productionToEdit: '',
          q: new FormData(),
          qNom:'',
          qPrenom:'',
          qTel:'',
          qEmail:'',
          hidden:'true',
          id:'',
          baseUrl:process.env.MIX_URL,
          loading:true
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.get(this.baseUrl+"/productions/liste")
        .then(response => {
          this.productions = response.data
          this.loading = false;
        })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.get(this.baseUrl+'/productions/liste?page=' + page)
          .then(response => {
            this.productions = response.data;
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
        refreshDeleted(productions){
          this.productions = productions.data;
          var value = 'Production a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(productions){
          var value = 'Production a été modifié avec succées';
          this.productions = productions.data;
          this.toast(value);
        },
        refreshAdded(productions){
          var value = 'Production a été ajouté avec succées';
          this.productions = productions.data;
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
          this.q.append('role', 'PRODUCTION');
          this.q.append('nom', this.qNom);
          this.q.append('prenom', this.qPrenom);
          this.q.append('tel', this.qTel);
          this.q.append('email', this.qEmail);

          axios.post(this.baseUrl+"/users/search", this.q)
          .then(response => this.productions = response.data)
          .catch(error => console.log(error))

        },
        refresh(productions){
          this.productions = productions.data; 
        },
        getProduction(id){
            axios.get(this.baseUrl+'/productions/edit/' + id)
            .then(response => this.productionToEdit = response.data)
            .catch(error => console.log(error));
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>