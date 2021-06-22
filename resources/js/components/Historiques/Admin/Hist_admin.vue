<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Interventions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/">Acceuil</router-link></li>
              <li class="breadcrumb-item active">Interventions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  <div class="card m-lg-4">
    <template v-if="hidden == 'false'">
      <div class="card m-lg-4">
      <div class="card-body">
        <div class="card-header">
          <h3 class="card-title">Recherche</h3>
        </div>
        <div class="row">
          <div class="col">
            <div class="from-group">
              <label>N°Bt</label>
              <input
                @keyup="search"
                type="text"
                v-model="qNumBt"
                class="form-control"
                placeholder="Num Bt"
              />
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label>Heure début</label>
              <input
                @change="search"
                type="time"
                v-model="qDateDebut"
                class="form-control"
              />
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label>Heure fin</label>
              <input
                @change="search"
                type="time"
                v-model="qDateFin"
                class="form-control"
              />
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
                <option value="Controle éléctrique">
                  Controle éléctrique
                </option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="from-group">
              <label>Technicien</label>
              <select @change="search" class="form-control" v-model="qTech">
                <option value="" selected>Vide</option>
                <option
                  v-for="tech in techs"
                  :key="tech.id"
                  :value="tech.id"
                >
                  {{ tech.nom }} {{ tech.prenom }}
                </option>
              </select>
            </div>
          </div>
          <div class="col">
            <label for="code_machine">Code machine</label>
            <input type="text" v-model="qCodeMachine" @keyup="search" class="form-control">
          </div>
          <div class="col">
            <div class="form-group">
              <label>BT cloturé</label>
              <select
                @change="search"
                class="form-control"
                v-model="qAppelle"
              >
                <option value="" selected>Vide</option>
                <option value="Cloturé">Cloturé</option>
                <option value="Non cloturé">Non cloturé</option>
              </select>
            </div>
          </div>
          <div class="col">
            <label>Categorie</label>
            <input type="text" v-model="qCodeCategorie" @keyup="search" class="form-control">
          </div>
        </div>
      </div>
    </div>
    </template>
    <div class="card-header">
      <h3 class="card-title">Liste de tous les interventions</h3>
      <div class="card-tools">
        <!-- Button trigger modal -->
        <button @click="exportData()" class="btn btn-secondary">
          <i class="fas fa-upload" title="Exporter les données"> Exporter</i>
        </button>
        <!-- <button class="btn btn-secondary" data-toggle="modal" data-target="#addFromExcel">
          <i class="fas fa-download" title="Ajouter via fichier excel"> Importer</i>
        </button> -->
        <template v-if="user.role == 'ADMIN'">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
        <i class="fas fa-plus" title="Ajouter une nouvelle intervention"> Ajouter</i>
        
        </button></template>
        <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
  <AddHistAdmin @historique-added="refreshAdded"></AddHistAdmin>  
  <AddFromExcel v-bind:type="'historiques'" @historique-added="refreshAdded"></AddFromExcel>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>N°Bt</th>
            <th>Jour</th>
            <th>H.demande</th>
            <th>H.début</th>
            <th>H.fin</th>
            <th>H.d'arret</th>
            <th>H.d'attente</th>
            <th>Type Travaille</th>
            <th>Code machine</th>
            <th>Designation M</th>
            <th>N°Serie</th>
            <th>Emplacement</th>
            <th>Description</th>
            <th>Zone</th>
            <th>Travaille éffectué</th>
            <th>Pièce de rechange</th>
            <th>BT cloturé</th>
            <template v-if="user.role == 'ADMIN'">
              <th>Action</th>
            </template>
          </tr>
        </thead>
        <tbody>
          <tr v-for="historique in historiques.data" :key="historique.id">
            <td>{{ historique.id }}</td>
            <td>{{ historique.jour }}</td>
            <td>{{ historique.heure_demande }}</td>
            <td>{{ historique.heure_debut }}</td>
            <td>{{ historique.heure_fin }}</td>
            <td>{{ historique.heure_arret }}</td>
            <td>{{ historique.heure_attente }}</td>
            <td>{{ historique.type_travaille }}</td>
            <td>{{ historique.code }}</td>
            <td>{{ historique.designation }}</td>
            <td>{{ historique.n_serie }}</td>
            <td>{{ historique.emplacement }}</td>
            <td>{{ historique.description_demande }}</td>
            <td>{{ historique.zone }}</td>
            <td>{{ historique.travaille }}</td>
            <td>{{ historique.piece_rechange }}</td>
            <td>{{ historique.appelle }}</td>
            <template v-if="user.role == 'ADMIN'">
            <td> 
               <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getHistorique(historique.id);">
              <i class="fas fa-edit" title="Modifier"/>
              </button>
               <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#showModal" @click="getHistorique(historique.id);">
                <i class="far fa-list-alt" title="Intervention"/>
                 </button>
              <button @click="setId(historique.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger"><i class="fas fa-trash-alt" title="Supprimer"/></button>
              <DeleteHist v-bind:id="id" @historique-deleted="refreshDeleted"></DeleteHist>
              <EditHistAdmin 
                v-bind:historiqueToEdit="historiqueToEdit"
                v-bind:tech="tech"
                v-bind:equipements="equipements"
                v-bind:codePannes="codePannes" 
                @historique-updated="refreshEdited">
              </EditHistAdmin> 
              <ShowHistAdmin
                v-bind:historiqueShow="historiqueShow">
              </ShowHistAdmin>
            </td></template>
          </tr> 
        </tbody>
      </table>
      <pagination class="m-auto" :limit="5"  :data="historiques" @pagination-change-page="getResults"></pagination>
    </div>
    <!-- /.card-body -->
  </div>
  </div>
</template>
<script>
  import AddHistAdmin from './AddHistAdmin';
  import EditHistAdmin from './EditHistAdmin';
  import ShowHistAdmin from './ShowHistAdmin';
  import DeleteHist from '../DeleteHistoriques';
  import AddFromExcel from '../../AddFromExcel';
export default {
    components:{
      AddHistAdmin,
      EditHistAdmin,
      ShowHistAdmin,
      DeleteHist,
      AddFromExcel
    },
  props:['user'],
    data(){
      return{
        historiques: {},
        historiqueToEdit: '',
        tech: '',
        techs: '',
        qNumBt: "",
        qZone: "",
        qDateDebut: "",
        qDateFin:"",
        qAppelle: "",
        qCodeCategorie:"",
        qCodeMachine:"",
        qCodePanne:"",
        allCodePanne:'',
        qTech: "",
        q: new FormData(),
        id: '',
        techId: '',
        equipements: '',
        codePannes: '',
        hidden:'true',
        loading:true,
        historiqueShow:''
      }
    },
    async created(){
      console.log(this.user.role);
      if(this.user.role == 'ADMIN' || this.user.role == 'RESPONSABLE'){
        await axios.post("/historiques/liste")
        .then(response => {
          this.historiques = response.data
        })
        .catch(error => console.log(error))
        await axios.get("/historiques/techs")
        .then(response => {
          this.techs = response.data
        })
        .catch(error => console.log(error))
        await axios.get('/code_pannes/liste-all')
        .then(response => {
          this.allCodePanne = response.data
          this.loading = false;
        })    
      }
      else{
        this.$router.push('/');
      }
    },
    methods:{
      exportData(){
        axios.get('historiques/export', {responseType: 'blob'})
        .then((response) => {
            const url = URL.createObjectURL(new Blob([response.data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'InterventionsListe')
            document.body.appendChild(link)
            link.click()
        });
      },
      getResults(page = 1) {
        axios.post('/historiques/liste?page=' + page)
        .then(response => {
          this.historiques = response.data;
        })
        .catch(error => console.log(error));
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
      setId(id){
        this.id = id
      },
      refreshDeleted(historiques){
        this.historiques = historiques.data;
        var value = 'Historique a été supprimé avec succées';
        this.toast(value);
      },
      refreshEdited(historiques){
        var value = 'Historique a été modifié avec succées';
        this.historiques = historiques.data;
        this.toast(value);
      },
      refreshAdded(historiques){
        var value = 'Historique a été ajouté avec succées';
        this.historiques = historiques.data;
        this.toast(value);
      },
      search(){
        this.q.append("num_bt", this.qNumBt);
        this.q.append("zone", this.qZone);
        this.q.append("date_debut", this.qDateDebut);
        this.q.append("date_fin", this.qDateFin);
        this.q.append("appelle", this.qAppelle);
        this.q.append("code_categorie", this.qCodeCategorie)
        this.q.append("code_equip", this.qCodeMachine)
        this.q.append("code_panne", this.qCodePanne)
        this.q.append("tech_id", this.qTech);
        this.q.append("type","ADMIN");

        axios.post("/historiques/liste", this.q)
        .then(response => this.historiques = response.data)
        .catch(error => console.log(error))
      },
      async getHistorique(id){
        await axios.get('/historiques/edit/' + id)
        .then(response => {
          this.historiqueToEdit = response.data
          this.historiqueShow = response.data[0]
        })
        .catch(error => console.log(error));  
        await axios.get("/historiques/techniciens/" + this.historiqueToEdit[0].zone)
        .then(response => {
          this.tech = response.data
        }) 
        .catch(error => console.log(error))
        await axios.get('/historiques/equipement/zone/' + this.historiqueToEdit[0].zone)
        .then(response =>{
          this.equipements =  response.data;
        })
        .catch(error => console.log(error));
        await axios.get("/historiques/code-panne/" + this.historiqueToEdit[0].zone)
        .then(response => {
          this.codePannes = response.data;
        })
        .catch(error => console.log(error))
      },
     
    }
  }
</script>