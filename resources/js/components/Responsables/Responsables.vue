<template>
    <div class="card">
    <div class="card-header">
      <h3 class="card-title">Liste de tous les responsables</h3>
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
    <!-- @responsable-added="refresh" -->
  <add-responsable @responsable-added="refresh"></add-responsable>
    <!-- <button>Ajouter un nouveau responsable</button> -->    
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="responsable in responsables" :key="responsable.id">
            <td>{{ responsable.id }}</td>
            <td>{{ responsable.nom }}</td>
            <td>{{ responsable.prenom }}</td>
            <td>{{ responsable.pseudo }}</td>
            <td>{{ responsable.email }}</td>
            <td>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getResponsable(responsable.id)">
              Modifier
              </button>
              <button type="button" class="btn btn-danger" @click="deleteResponsable(responsable.id)">Supprimer</button>
              <edit-responsable v-bind:responsableToEdit="responsableToEdit" @responsable-updated="refresh"></edit-responsable>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</template>
<script>
    export default {
      data(){
        return{
          responsables: {},
          responsableToEdit: '',
        }
      },
      created(){
        axios.get("http://localhost:8000/responsables/liste")
        .then(response => this.responsables = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        refresh(responsables){
          this.responsables = responsables.data; 
        },
        getResponsable(id){
            axios.get('http://localhost:8000/responsables/edit/' + id)
            .then(response => this.responsableToEdit = response.data)
            .catch(error => console.log(error));
        },
        deleteResponsable(id){
            axios.delete('http://localhost:8000/responsables/' + id)
            .then(response => this.responsables = response.data)
            .catch(error => console.log(error));               
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>