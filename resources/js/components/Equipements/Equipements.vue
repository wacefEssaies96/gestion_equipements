<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Equipements</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Equipements</li>
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
      </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les equipements</h3>
        <div class="card-tools">
          <router-link class="btn btn-outline-info" to="/onedrive">Ajouter via Onedrive</router-link>
                  <!-- Button trigger modal -->
        <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
        Ajouter un nouveau equipement
        </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
    <add-equipement @equipement-added="refreshAdded"></add-equipement>
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
            <tr v-for="equipement in equipements.data" :key="equipement.id">
              <td>{{ equipement.nom }}</td>
              <td>{{ equipement.code }}</td>
              <td>{{ equipement.designation }}</td>
              <td>{{ equipement.n_serie }}</td>
              <td><img :src="equipement.image" style="width:100px; height:100px"></td>
              <td>{{ equipement.zone }}</td>
              <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getEquipement(equipement.id)">
                Modifier
                </button>
                <button @click="setId(equipement.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-danger">Supprimer</button>
                <delete-equipement v-bind:id="id" @equipement-deleted="refreshDeleted"></delete-equipement>
                <edit-equipement v-bind:equipementToEdit="equipementToEdit" @equipement-updated="refreshEdited"></edit-equipement>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="equipements" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  export default {
    props:['user'],
    data(){
      return{
        equipements: {},
        equipementToEdit: '',
        q: new FormData(),
        qDesignation: '',
        qZone: '',
        qNserie: '',
        qNom: '',
        qCodeMachine: '',
        hidden: 'true',
        id:''
      }
    },
    created(){
      if(this.user.role != 'ADMIN'){
        this.$router.push('/');
      }
      axios.post("http://localhost:8000/equipements/liste")
      .then(response => this.equipements = response.data)
      .catch(error => console.log(error))
    },
    methods:{
      getResults(page = 1) {
        axios.post('http://localhost:8000/equipements/liste?page=' + page)
        .then(response => {
          this.equipements = response.data;
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
      refreshDeleted(equipements){
        this.equipements = equipements.data;
        var value = 'Equipement a été supprimé avec succées';
        this.toast(value);
      },
      refreshEdited(equipements){
        var value = 'Equipement a été modifié avec succées';
        this.equipements = equipements.data;
        this.toast(value);
      },
      refreshAdded(equipements){
        var value = 'Equipement a été ajouté avec succées';
        this.equipements = equipements.data;
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
    }
  }
</script>