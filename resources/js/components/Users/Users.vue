<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Utilisateurs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Utilisateurs</li>
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
              <div class="col">
                <div class="from-group">
                  <label>Role</label>
                  <select @change="search" v-model="qRole" class="form-control">
                    <option value="">Vide</option>
                    <option value="TECHNICIEN">Technicien</option>
                    <option value="PRODUCTION">Production</option>
                    <option value="HOTLINE">Hotline</option>
                    <option value="RESPONSABLE">Responsable</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les utilisateurs</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau utilisateur
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
    <!-- /.card-header -->
      <AddUser @user-added="refresh"></AddUser> 
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Role</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users.data" :key="user.id">
              <td>{{ user.nom }}</td>
              <td>{{ user.prenom }}</td>
              <td>{{ user.role }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.tel }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getUser(user.id)">
                <i class="fas fa-edit" title="Modifier"/>
                </button>
                <button @click="setId(user.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger"><i class="fas fa-trash-alt" title="Supprimer"/></button>
                <DeleteUser v-bind:id="id" @user-deleted="refreshDeleted"></DeleteUser>
                <EditUser 
                    v-bind:userToEdit="userToEdit"
                    @user-updated="refreshEdited">
                </EditUser>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="users" @pagination-change-page="getResults"></pagination>
      </div>
    <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddUser from './AddUser';
  import EditUser from './EditUser';
  import DeleteUser from './DeleteUser';
    export default {    
      components:{
      AddUser,
      EditUser,
      DeleteUser
    },
      
      props:['user'],
      data(){
        return{
          users: {},
          userToEdit: '',
          id:'',
          q: new FormData(),
          qNom:'',
          qPrenom:'',
          qTel:'',
          qEmail:'',
          qRole:'',
          hidden:'true',
          loading:true,
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.get("/users")
        .then(response => {
          this.users = response.data;
          this.loading = false;
        })
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.get('/users?page=' + page)
          .then(response => {
            this.users = response.data;
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
          this.q.append('role', this.qRole);
          this.q.append('nom', this.qNom);
          this.q.append('prenom', this.qPrenom);
          this.q.append('tel', this.qTel);
          this.q.append('email', this.qEmail);

          axios.post("http://localhost:8000/users/search", this.q)
          .then(response => this.users = response.data)
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
        refreshDeleted(users){
          this.users = users.data;
          var value = 'Utilisateur a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(users){
          var value = 'Utilisateur a été modifié avec succées';
          this.users = users.data;
          this.toast(value);
        },
        refresh(users){
          var value = 'Utilisateur a été ajouté avec succées';
          this.users = users.data;
          this.toast(value);
        },
        getUser(id){
          axios.get('/users/edit/' + id)
          .then(response => {
              this.userToEdit = response.data;
                console.log(response.data)  
            })
          .catch(error => console.log(error));
        },
        
      },
    }
</script>