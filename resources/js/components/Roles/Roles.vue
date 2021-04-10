<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Roles</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Roles</li>
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
        <h3 class="card-title">Liste de tous les roles</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau Role
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
    <AddRole @role-added="refreshAdded"></AddRole>   
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Designation</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="role in roles.data" :key="role.id">
              <td>{{ role.designation }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getRole(role.id)">
                  <i class="fas fa-edit" title="Modifier"/>
                </button>              
                <button @click="setId(role.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger">
                   <i class="fas fa-trash-alt" title="Supprimer"/>
                  </button>
                <DeleteRole v-bind:id="id" @role-deleted="refreshDeleted"></DeleteRole>
                <EditRole v-bind:roleToEdit="roleToEdit" @role-updated="refreshEdited"></EditRole>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="roles" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddRole from './AddRole';
  import EditRole from './EditRole';
  import DeleteRole from './DeleteRole';
    export default {
        components:{
          AddRole,
          EditRole,
          DeleteRole,
      },
      props:['user'],
      data(){
        return{
          roles: {},
          roleToEdit: '',
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
        axios.post("/roles/liste")
        .then(response => {
          this.roles = response.data;
          this.loading = false;
          })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.post('/roles/liste?page=' + page)
          .then(response => {
            this.roles = response.data;
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
        refreshDeleted(roles){
          this.roles = roles.data;
          var value = 'Role a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(roles){
          var value = 'Role a été modifié avec succées';
          this.roles = roles.data;
          this.toast(value);
        },
        refreshAdded(roles){
          var value = 'Role a été ajouté avec succées';
          this.roles = roles.data;
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

          axios.post("/roles/liste", this.q)
          .then(response => this.roles = response.data)
          .catch(error => console.log(error))

        },
        refresh(roles){
          this.roles = roles.data; 
        },
        getRole(id){
            axios.get('/roles/edit/' + id)
            .then(response => this.roleToEdit = response.data)
            .catch(error => console.log(error));
        },
        
      },
    }
</script>