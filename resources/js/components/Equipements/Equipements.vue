<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Equipements</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/">Acceuil</router-link></li>
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
            <div class="row">
              <div class="col">
                <div class="from-group">
                  <label>Categorie</label>
                  <input @keyup="search" type="text" v-model="qCategorie" class="form-control" placeholder="Categorie">
                </div>
              </div>
              <div class="col">
                <div class="from-group">
                  <label>Constructeur</label>
                  <input @keyup="search" type="text" v-model="qConstructeur" class="form-control" placeholder="Constructeur">
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les equipements</h3>
        <div class="card-tools">
          <template v-if="accessData.userName == null">
            <a href="/signin" class="btn btn-success">
              <i class="fas fa-sign-in-alt" title="Se connecter à OneDrive"/>
              <i class="nav-icon fas fa-cloud"/> Onedrive
            </a> 
          </template>
          <template v-if="accessData.userName != null">
            <h4 class="font-weight-bold text-success">Bienvenue <u> {{ accessData.userName }}  ! </u></h4>
            <a href="/signout" class="btn btn-danger">
              <i class="fas fa-sign-out-alt" title="Se déconnecter de OneDrive"></i>
              <i class="nav-icon fas fa-cloud"/>
            </a>
            <button class="btn btn-outline-info" data-toggle="modal" data-target="#addList">
              <i class="fas fa-file-download" title="Importer via Onedrive"> Importer via OneDrive</i>
            </button>
            <AddList @list-added="refreshAdded"></AddList>
          </template>
          <button @click="exportData()" class="btn btn-secondary">
             <i class="fas fa-upload" title="Exporter les données"> Exporter</i>
          </button>
          <!-- Button trigger modal -->
          <button class="btn btn-secondary" data-toggle="modal" data-target="#addFromExcel">
            <i class=" fas fa-download" title="Ajouter via fichier excel"> Importer</i>
          </button>
        <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
          <i class="fas fa-plus" title=" Ajouter un nouvel équipement">  Ajouter</i>
        </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
    <AddEquipement @equipement-added="refreshAdded" v-bind:isConnected="isConnected"></AddEquipement>
    <AddFromExcel v-bind:type="'equipement'" @codePanne-added="refreshAdded"></AddFromExcel>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Code_machine</th>
              <th>Designation</th>
              <th>Code catégorie</th>
              <th>Date d'acquisition</th>
              <th>N°serie</th>
              <th>Constructeur</th>
              <th>Etat</th>
              <th>Image</th>
              <th>Zone</th>  
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="equipement in equipements.data" :key="equipement.id">
              <td>{{ equipement.code }}</td>
              <td>{{ equipement.designation }}</td>
              <td>{{ equipement.code_categorie }}</td>
              <td>{{ equipement.date_acq }}</td>
              <td>{{ equipement.n_serie }}</td>
              <td>{{ equipement.constructeur }}</td>
              <td>{{ equipement.etat }}</td>
              <td><img :src="equipement.image" style="width:100px; height:100px"></td>
              <td>{{ equipement.zone }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getEquipement(equipement.id)" title="Modifier">
                <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#showModal" @click="getEquipement(equipement.id)">
                <i class="far fa-list-alt" title="Equipement"/>
                 </button>
                <button @click="setId(equipement.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger" title="Supprimer"><i class="fas fa-trash-alt" alt="Supprimer"></i></button>
                <DeleteEquipement v-bind:id="id" @equipement-deleted="refreshDeleted"></DeleteEquipement>
                <EditEquipement 
                  v-bind:equipementToEdit="equipementToEdit" 
                  v-bind:isConnected="isConnected"
                  @equipement-updated="refreshEdited">
                </EditEquipement>
                <ShowEquipement
                    v-bind:equipementShow="equipementToEdit">
                 </ShowEquipement>
              </td>
            </tr>
          </tbody>
        </table>
        <button hidden id="openPdf" data-toggle="modal" data-target="#viewpdf"></button>
        <pagination class="m-auto" 
          :data="equipements" 
          @pagination-change-page="getResults"
          :limit="5" 
        >
        </pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddEquipement from './AddEquipement';
  import EditEquipement from './EditEquipement';
  import ShowEquipement from './ShowEquipement';
  import DeleteEquipement from './DeleteEquipement';
  import AddList from '../OneDrive/AddFileList';
  import AddFromExcel from '../AddFromExcel';

  export default {
    components:{
      AddEquipement,
      EditEquipement,
      ShowEquipement,
      DeleteEquipement,
      AddList,
      AddFromExcel
    },
    props:['user'],
    data(){
      return{
        equipements: {},
        equipementToEdit: '',
        q: new FormData(),
        qCategorie:'',
        qDesignation: '',
        qZone: '',
        qNserie: '',
        qConstructeur: '',
        qCodeMachine: '',
        hidden: 'true',
        id:'',
        files: '',
        file:'',
        accessData: '',
        isConnected: '',
        accessData:'',
        document:'',
        loading:true,
      }
    },
    async created(){
      if(this.user.role != 'ADMIN'){
        this.$router.push('/');
      }
      await axios.get("/getAccessData")
      .then(response => this.accessData = JSON.parse(response.request.response))
      .catch(error => console.log(error))
      await axios.post("/equipements/liste")
      .then(response => {
        this.equipements = response.data;
        this.loading = false;
      })
      .catch(error => console.log(error))
      if(this.accessData.userName != null){
        this.isConnected = true;
      }
      else{
        this.isConnected = false;
      }
    },
    methods:{
      exportData(){
        axios.get('equipements/export', {responseType: 'blob'})
        .then((response) => {
            const url = URL.createObjectURL(new Blob([response.data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'EquipementsListe')
            document.body.appendChild(link)
            link.click()
        });
      },
      getResults(page = 1) {
        axios.post('/equipements/liste?page=' + page)
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
        this.q.append('code_categorie', this.qCategorie);
        this.q.append('constructeur', this.qConstructeur);
        this.q.append('zone', this.qZone);
        this.q.append('designation', this.qDesignation);
        this.q.append('n_serie', this.qNserie);
        this.q.append('code', this.qCodeMachine);

        axios.post("/equipements/liste", this.q)
        .then(response => this.equipements = response.data)
        .catch(error => console.log(error))

      },
      refresh(equipements){
        this.equipements = equipements.data; 
      },
      getEquipement(id){
        axios.get('/equipements/edit/' + id)
        .then(response => this.equipementToEdit = response.data)
        .catch(error => console.log(error));
      },
    }
  }
</script>