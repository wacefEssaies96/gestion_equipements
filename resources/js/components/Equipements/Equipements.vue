<template>
    <div class="card">
            <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="from-group">
                <label>Nom</label>
                <input @keyup="search" type="text" v-model="qNom" class="form-control" placeholder="Nom Machine">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Code Machine</label>
                <input @keyup="search" type="text" v-model="qCodeMachine" class="form-control" placeholder="Code Machine">
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
          <div class="row">
            <div class="col">
              <div class="from-group">
                <label>Désignation</label>
                  <input @keyup="search" type="text" v-model="qDesignation" class="form-control" placeholder="Désignation">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
              <label>N° serie</label>
                <input @keyup="search" type="text" v-model="qNserie" class="form-control" placeholder="N° serie">
            </div>
            </div>
          </div>
        </div>
      </div>
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
            <td><img :src="equipement.image" style="width:50px; height:50px"></td>
            <td>{{ equipement.zone }}</td>
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
    </div>
    <!-- /.card-body -->
  </div>
</template>
<script>
  export default {
    data(){
      return{
        equipements: {},
        equipementToEdit: '',
        q: new FormData(),
        qDesignation: '',
        qZone: '',
        qNserie: '',
        qNom: '',
        qCodeMachine: ''
      }
    },
    created(){
      axios.post("http://localhost:8000/equipements/liste")
      .then(response => this.equipements = response.data)
      .catch(error => console.log(error))
    },
    methods:{
      search(){
        this.q.append('nom', this.qNom);
        this.q.append('zone', this.qZone);
        this.q.append('designation', this.qDesignation);
        this.q.append('n_serie', this.qNserie);
        this.q.append('code', this.qCodeMachine);

        axios.post("http://localhost:8000/equipements/liste", this.q)
        .then(response => this.equipements = response.data)
        .catch(error => console.log(error))

      },
      refresh(equipements){
        this.equipements = equipements.data; 
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
    }
  }
</script>