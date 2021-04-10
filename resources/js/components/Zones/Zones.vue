<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Zonnes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Zones</li>
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
                  <div class="form-group">
                    <label>Désignation</label>
                    <input @keyup="search" type="text" v-model="qDesi" class="form-control" placeholder="Désignation">
                  </div>
                </div>
            
              </div>
            </div>
          </div>
        </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les zones</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau Zone
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
    <AddZone @zone-added="refreshAdded"></AddZone>   
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Designation</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="zone in zones.data" :key="zone.id">
              <td>{{ zone.designation }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getZone(zone.id)">
                  <i class="fas fa-edit" title="Modifier"/>
                </button>              
                <button @click="setId(zone.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger">
                   <i class="fas fa-trash-alt" title="Supprimer"/>
                  </button>
                <DeleteZone v-bind:id="id" @zone-deleted="refreshDeleted"></DeleteZone>
                <EditZone v-bind:zoneToEdit="zoneToEdit" @zone-updated="refreshEdited"></EditZone>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="zones" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddZone from './AddZone';
  import EditZone from './EditZone';
  import DeleteZone from './DeleteZone';
    export default {
        components:{
          AddZone,
          EditZone,
          DeleteZone,
      },
      props:['user'],
      data(){
        return{
          zones: {},
          zoneToEdit: '',
          qDesi: '',
          q: new FormData(),
          hidden: 'true',
          id:'',
          loading:true,
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.post("/zones/liste")
        .then(response => {
          this.zones = response.data;
          this.loading = false;
          })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.post('/zones/liste?page=' + page)
          .then(response => {
            this.zones = response.data;
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
        refreshDeleted(zones){
          this.zones = zones.data;
          var value = 'Zone a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(zones){
          var value = 'Zone a été modifié avec succées';
          this.zones = zones.data;
          this.toast(value);
        },
        refreshAdded(zones){
          var value = 'Zone a été ajouté avec succées';
          this.zones = zones.data;
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
          this.q.append('designation', this.qDesi);

          axios.post("/zones/liste", this.q)
          .then(response => this.zones = response.data)
          .catch(error => console.log(error))

        },
        refresh(zones){
          this.zones = zones.data; 
        },
        getZone(id){
            axios.get('/zones/edit/' + id)
            .then(response => this.zoneToEdit = response.data)
            .catch(error => console.log(error));
        },
        
      },
    }
</script>