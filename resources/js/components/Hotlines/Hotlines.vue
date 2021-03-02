<template>
    <div class="card">
    <div class="card-header">
      <h3 class="card-title">Liste de tous les hotlines</h3>
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
    <!-- @hotline-added="refresh" -->
  <add-hotline @hotline-added="refresh"></add-hotline>
    <!-- <button>Ajouter un nouveau hotline</button> -->    
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
          <tr v-for="hotline in hotlines" :key="hotline.id">
            <td>{{ hotline.id }}</td>
            <td>{{ hotline.nom }}</td>
            <td>{{ hotline.prenom }}</td>
            <td>{{ hotline.pseudo }}</td>
            <td>{{ hotline.email }}</td>
            <td>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getHotline(hotline.id)">
              Modifier
              </button>
              <button type="button" class="btn btn-danger" @click="deleteHotline(hotline.id)">Supprimer</button>
              <edit-hotline v-bind:hotlineToEdit="hotlineToEdit" @hotline-updated="refresh"></edit-hotline>
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
          hotlines: {},
          hotlineToEdit: '',
        }
      },
      created(){
        axios.get("http://localhost:8000/hotlines/liste")
        .then(response => this.hotlines = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        refresh(hotlines){
          this.hotlines = hotlines.data; 
        },
        gethotline(id){
            axios.get('http://localhost:8000/hotlines/edit/' + id)
            .then(response => this.hotlineToEdit = response.data)
            .catch(error => console.log(error));
        },
        deleteHotline(id){
            axios.delete('http://localhost:8000/hotlines/' + id)
            .then(response => this.hotlines = response.data)
            .catch(error => console.log(error));               
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>