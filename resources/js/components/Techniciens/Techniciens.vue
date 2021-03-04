<template>
    <div class="card">
    <div class="card-header">
      <h3 class="card-title">Liste de tous les techniciens</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 150px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Recherche">

          <div class="input-group-append">
            <button type="submit" class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-header -->
  <add-technicien @technicien-added="refresh"></add-technicien>   
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Pseudo</th>
            <th>Téléphone</th>
            <th>Email</th>
            <!-- <th>Qualification</th> -->
            <th>Zone</th>
            <!-- <th>Heures de début de services</th>
            <th>Heures de fin de services</th> -->
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="technicien in techniciens" :key="technicien.id">
            <td>{{ technicien.nom }}</td>
            <td>{{ technicien.prenom }}</td>
            <td>{{ technicien.pseudo }}</td>
            <td>{{ technicien.tel }}</td>
            <td>{{ technicien.email }}</td>
            <template v-for="tech in technicien.techniciens">
              <td :key=tech.zone>{{ tech.zone }}</td>
            </template>
            <td>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getTechnicien(technicien.id)">
              Modifier
              </button>
              <button type="button" class="btn btn-danger" @click="deleteTechnicien(technicien.id)">Supprimer</button>
              <edit-technicien v-bind:technicienToEdit="technicienToEdit" @technicien-updated="refresh"></edit-technicien>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <pagination :data="techniciens" @pagination-change-page="getResults"></pagination> -->
    </div>
    <!-- /.card-body -->
  </div>
</template>
<script>
    export default {
      data(){
        return{
          techniciens: {},
          technicienToEdit: '',
        }
      },
      created(){
        axios.get("http://localhost:8000/techniciens/liste")
        .then(response => this.techniciens = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        refresh(techniciens){
          this.techniciens = techniciens.data; 
        },
        getResults(page = 1) {
          axios.get('http://localhost:8000/techniciens?page=' + page)
              .then(response => {
                  this.techniciens = response.data;
          });
        },
        getTechnicien(id){
            axios.get('http://localhost:8000/techniciens/edit/' + id)
            .then(response => this.technicienToEdit = response.data)
            .catch(error => console.log(error));
        },
        deleteTechnicien(id){
            axios.delete('http://localhost:8000/techniciens/' + id)
            .then(response => this.techniciens = response.data)
            .catch(error => console.log(error));               
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>