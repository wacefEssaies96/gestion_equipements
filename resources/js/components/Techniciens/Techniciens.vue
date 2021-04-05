<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Techniciens</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Techniciens</li>
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
                  <label>Zone</label>
                  <select v-model="qZone" @change="search" class="form-control">
                    <option value="">Vide</option>
                    <option value="Assemblage">Assemblage</option>
                    <option value="Sertissage">Sertissage</option>
                    <option value="Préparation">Préparation</option>
                    <option value="Coupe">Coupe</option>
                    <option value="Controle éléctrique">Controle éléctrique</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les techniciens</h3>
        <div class="card-tools">
          <!-- Button trigger modal -->
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          Ajouter un nouveau technicien
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
    <!-- /.card-header -->
      <AddTechnicien @technicien-added="refreshAdded"></AddTechnicien>   
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Téléphone</th>
              <th>Email</th>
              <th>Qualification</th>
              <th>Heure de début de service</th>
              <th>Heure de fin de service</th>
              <th>Zone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="technicien in techniciens.data" :key="technicien.id">
              <td>{{ technicien.nom }}</td>
              <td>{{ technicien.prenom }}</td>
              <td>{{ technicien.tel }}</td>
              <td>{{ technicien.email }}</td>
              <td>{{ technicien.qualification }}</td>
              <td>{{ technicien.h_debut_service }}</td>
              <td>{{ technicien.h_fin_service }}</td>
              <td>{{ technicien.zone }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getTechnicien(technicien.user_id)">
                <i class="fas fa-edit" title="Modifier"/>
                </button>
                <button @click="setId(technicien.user_id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger"><i class="fas fa-trash-alt" title="Supprimer"/></button>
                <DeleteTechnicien v-bind:id="id" @technicien-deleted="refreshDeleted"></DeleteTechnicien>
                <EditTechnicien v-bind:technicienToEdit="technicienToEdit" @technicien-updated="refreshEdited"></EditTechnicien>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :data="techniciens" @pagination-change-page="getResults"></pagination>
      </div>
    <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddTechnicien from './AddTechnicien';
  import EditTechnicien from './EditTechnicien';
  import DeleteTechnicien from './DeleteTechnicien';
    export default {
    components:{
      AddTechnicien,
      EditTechnicien,
      DeleteTechnicien
    },
      props:['user'],
      data(){
        return{
          techniciens: {},
          technicienToEdit: '',
          q: new FormData(),
          qNom:'',
          qPrenom:'',
          qTel:'',
          qEmail:'',
          qZone:'',
          hidden:'true',
          id:'',
          loading:true,
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.get("/techniciens/liste")
        .then(response => {
          this.techniciens = response.data;
          this.loading = false;
        })
        .catch(error => console.log(error))
      },
      methods:{
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
        refreshDeleted(techniciens){
          this.techniciens = techniciens.data;
          var value = 'Technicien a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(techniciens){
          var value = 'Technicien a été modifié avec succées';
          this.techniciens = techniciens.data;
          this.toast(value);
        },
        refreshAdded(techniciens){
          var value = 'Technicien a été ajouté avec succées';
          this.techniciens = techniciens.data;
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
          this.q.append('role', 'TECHNICIEN');
          this.q.append('nom', this.qNom);
          this.q.append('prenom', this.qPrenom);
          this.q.append('tel', this.qTel);
          this.q.append('email', this.qEmail);
          this.q.append('zone', this.qZone);

          axios.post("/users/search", this.q)
          .then(response => this.techniciens = response.data)
          .catch(error => console.log(error))

        },
        refresh(techniciens){
          this.techniciens = techniciens.data; 
        },
        getResults(page = 1) {
          axios.get('/techniciens/liste?page=' + page)
              .then(response => {
                  this.techniciens = response.data;
          });
        },
        getTechnicien(id){
            axios.get('/techniciens/edit/' + id)
            .then(response => this.technicienToEdit = response.data)
            .catch(error => console.log(error));
        },
      }
    }
</script>