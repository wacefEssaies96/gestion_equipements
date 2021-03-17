<template>
    <div class="card">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="from-group">
                <label>Code</label>
                <input @keyup="search" type="text" v-model="qCode" class="form-control" placeholder="Code panne">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Désignation</label>
                <input @keyup="search" type="text" v-model="qDesi" class="form-control" placeholder="Désignation">
              </div>
            </div>
            <div class="col">
              <div class="from-group">
                <label>Zone</label>
                <select @change="search" class="form-control" v-model="qZone">
                    <option value="" selected>Vide</option>
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
        <!-- <div class="input-group input-group-sm" style="width: 100%;"> -->
          
        <!-- </div> -->
      </div>
    <div class="card-header">
      <h3 class="card-title">Liste de tous les codes pannes</h3>
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
  <add-codePanne @codePanne-added="refresh"></add-codePanne>   
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>Code Panne</th>
            <th>Designation</th>
            <th>Zone</th>  
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="code_panne in code_pannes" :key="code_panne.id">
            <td>{{ code_panne.code }}</td>
            <td>{{ code_panne.designation }}</td>
            <td>{{ code_panne.zone }}</td>
            <td>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getCode_Panne(code_panne.id)">
              Modifier
              </button>
              <button type="button" class="btn btn-danger" @click="deleteCode_Panne(code_panne.id)">Supprimer</button>
              <edit-codePanne v-bind:codePanneToEdit="codePanneToEdit" @codePanne-updated="refresh"></edit-codePanne>
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
          code_pannes: {},
          codePanneToEdit: '',
          qCode: '',
          qZone: '',
          qDesi: '',
          q: new FormData()
        }
      },
      created(){
        axios.post("http://localhost:8000/code_pannes/liste")
        .then(response => this.code_pannes = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        search(){
          this.q.append('code', this.qCode);
          this.q.append('zone', this.qZone);
          this.q.append('designation', this.qDesi);

          axios.post("http://localhost:8000/code_pannes/liste", this.q)
          .then(response => this.code_pannes = response.data)
          .catch(error => console.log(error))

        },
        refresh(code_pannes){
          this.code_pannes = code_pannes.data; 
        },
        getCode_Panne(id){
            axios.get('http://localhost:8000/code_pannes/edit/' + id)
            .then(response => this.codePanneToEdit = response.data)
            .catch(error => console.log(error));
        },
        deleteCode_Panne(id){
            axios.delete('http://localhost:8000/code_pannes/' + id)
            .then(response => this.code_pannes = response.data)
            .catch(error => console.log(error));               
        },
      },
    }
</script>