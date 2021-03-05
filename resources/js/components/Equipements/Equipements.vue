<template>
    <div class="card">
    <div class="card-header">
      <h3 class="card-title">Liste de tous les equipements</h3>
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
  <add-equipement @equipement-added="refresh"></add-equipement>   
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Code_machine</th>
            <th>Designation</th>
            <th>N°serie</th>
            <th>Image</th>
            <th>Zone</th>  
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="equipement in equipements" :key="equipement.id">
            <td>{{ equipement.nom }}</td>
            <td>{{ equipement.code }}</td>
            <td>{{ equipement.designation }}</td>
            <td>{{ equipement.n_serie }}</td>
            <td>{{ equipement.image }}</td>
            <template v-for="equi in equipement.equipements">
              <td :key=equi.zone>{{ equi.zone }}</td>
            </template>
            <td>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getEquipement(equipement.id)">
              Modifier
              </button>
              <button type="button" class="btn btn-danger" @click="deleteEquipement(equipement.id)">Supprimer</button>
              <edit-equipement v-bind:equipementToEdit="equipementToEdit" @equipement-updated="refresh"></edit-equipement>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <pagination :data="equipements" @pagination-change-page="getResults"></pagination> -->
    </div>
    <!-- /.card-body -->
  </div>
</template>
<script>
import AddEquipement from './AddEquipement.vue';
    export default {
  components: { AddEquipement },
      data(){
        return{
          equipements: {},
          equipementToEdit: '',
        }
      },
      created(){
        axios.get("http://localhost:8000/equipements/liste")
        .then(response => this.equipements = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        refresh(equipements){
          this.equipements = equipements.data; 
        },
        getResults(page = 1) {
          axios.get('http://localhost:8000/equipements?page=' + page)
              .then(response => {
                  this.equipements = response.data;
          });
        },
        getEquipement(id){
            axios.get('http://localhost:8000/equipements/edit/' + id)
            .then(response => this.equipementToEdit = response.data)
            .catch(error => console.log(error));
        },
        deleteEquipement(id){
            axios.delete('http://localhost:8000/equipements/' + id)
            .then(response => this.equipements = response.data)
            .catch(error => console.log(error));               
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>