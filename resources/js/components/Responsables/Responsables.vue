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
  <add-responsable ></add-responsable>
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
          </tr>
        </thead>
        <tbody>
          <tr v-for="responsable in responsables" :key="responsable.id">
            <td>{{ responsable.id }}</td>
            <td>{{ responsable.nom }}</td>
            <td>{{ responsable.prenom }}</td>
            <td>{{ responsable.pseudo }}</td>
            <td>{{ responsable.email }}</td>
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
          
        }
      },
      created(){
        axios.get("http://localhost:8000/responsables/liste")
        .then(response => this.responsables = response.data)
        .catch(error => console.log(error))
      },
      //single page application
      methods:{
        refresh(responsables){
          this.responsables = responsables.data; 
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>