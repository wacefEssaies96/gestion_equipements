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
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Interventions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div>
      <template v-if="hidden == 'false'"
        ><button @click="viewAll('true')">
          Afficher tous les interventions
        </button></template
      >
      <template v-if="hidden == 'true'"
        ><button @click="viewAll('false')">
          Afficher les interventions du hotline
        </button></template
      >
      <template v-if="hidden == 'false'">
        <div class="card m-lg-4" id="histhotline">
          <template v-if="hiddenS == 'false'">
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
                      @keyup="search('HOTLINE')"
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
                      @change="search('HOTLINE')"
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
                      @change="search('HOTLINE')"
                      type="time"
                      v-model="qDateFin"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Technicien</label>
                    <select @change="search('HOTLINE')" class="form-control" v-model="qTech">
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
                  <div class="form-group">
                    <label>BT cloturé</label>
                    <select
                      @change="search('HOTLINE')"
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
                  <input type="text" v-model="qCodeCategorie" @keyup="search('HOTLINE')" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Code panne</label>
                    <select @change="search('HOTLINE')" class="form-control" v-model="qCodePanne">
                      <option value="" selected>Vide</option>
                      <option
                        v-for="cp in allCodePanne"
                        :key="cp.id"
                        :value="cp.id"
                      >
                        {{ cp.code }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <label for="code_machine">Code machine</label>
                  <input type="text" v-model="qCodeMachine" @keyup="search('HOTLINE')" class="form-control">
                </div>
                 <div class="col">
                  <div class="from-group">
                    <label>Zone</label>
                    <select @change="search('HOTLINE')" class="form-control" v-model="qZone">
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
            </div>
          </div>
          </template>
          <div class="card-header">
            <h3 class="card-title">Liste de tous les interventions d'hotline</h3>
            <div class="card-tools">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
              Ajouter un nouveau intervention
              </button>
              <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <AddHistorique 
            @historique-added="refreshAdded"
          ></AddHistorique>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>N°Bt</th>
                  <th>Jour</th>
                  <th>Heure de demande</th>
                  <th>Type Travaille</th>
                  <th>Code machine</th>
                  <th>Designation M</th>
                  <th>N°Serie</th>
                  <th>Emplacement</th>
                  <th>Description</th>
                  <th>Zone</th>
                  <th>H.début</th>
                  <th>H.fin</th>
                  <th>Code panne</th>
                  <th>Designation CP</th>
                  <th>Travaille éffectué</th>
                  <th>Pièce de rechange</th>
                  <th>H.d'arret</th>
                  <th>H.d'attente</th>
                  <th>BT cloturé</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="histHotline in histsHotline.data"
                  :key="histHotline.id"
                >
                  <td>{{ histHotline.id }}</td>
                  <td>{{ histHotline.jour }}</td>
                  <td>{{ histHotline.heure_demande }}</td>
                  <td>{{ histHotline.type_travaille }}</td>
                  <td>{{ histHotline.code_equip }}</td>
                  <td>{{ histHotline.designation }}</td>
                  <td>{{ histHotline.n_serie }}</td>
                  <td>{{ histHotline.emplacement }}</td>
                  <td>{{ histHotline.description_demande }}</td>
                  <td>{{ histHotline.zone }}</td>
                  <td>{{ histHotline.heure_debut }}</td>
                  <td>{{ histHotline.heure_fin }}</td>
                  <td>{{ histHotline.codePanne }}</td>
                  <td>{{ histHotline.codePanneDesignation }}</td>
                  <td>{{ histHotline.travaille }}</td>
                  <td>{{ histHotline.piece_rechange }}</td>
                  <td>{{ histHotline.heure_arret }}</td>
                  <td>{{ histHotline.heure_attente }}</td>
                  <td>{{ histHotline.appelle }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-outline-info"
                      data-toggle="modal"
                      data-target="#editModal"
                      @click="getHistorique(histHotline.id)"
                    >
                      <i class="fas fa-edit" title="Modifier"/>
                    </button>
                    <button
                      @click="setId(histHotline.id)"
                      data-toggle="modal"
                      data-target="#modal-danger"
                      type="button"
                      class="btn btn-outline-danger"
                    >
                      <i class="fas fa-trash-alt" title="Supprimer"/>
                    </button>
                    <DeleteHist
                      v-bind:id="id"
                      @historique-deleted="refreshDeleted"
                    ></DeleteHist>
                    <EditHistorique
                      v-bind:historiqueToEdit="historiqueToEdit"
                      v-bind:tech="tech"
                      v-bind:equipements="equipements"
                      @historique-updated="refreshEdited"
                    >
                    </EditHistorique>
                  </td>
                </tr>
              </tbody>
            </table>
            <pagination
              class="m-auto"
              :data="histsHotline"
              @pagination-change-page="getResultsForHotline"
              :limit="5" 
            ></pagination>
          </div>

          <!-- /.card-body -->
        </div>
      </template>
      <template v-if="hidden == 'true'">
        <div class="card m-lg-4" id="histall">
          <template v-if="hiddenS == 'false'">
            <div class="card m-lg-4">
              <div class="card-header">
                <h3 class="card-title">Recherche</h3>
              </div>
              <div class="card-body">
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Num Bt</label>
                    <input
                      @keyup="search('all')"
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
                      @change="search('all')"
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
                      @change="search('all')"
                      type="time"
                      v-model="qDateFin"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Technicien</label>
                    <select @change="search('all')" class="form-control" v-model="qTech">
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
                  <div class="form-group">
                    <label>BT cloturé</label>
                    <select
                      @change="search('all')"
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
                  <input type="text" v-model="qCodeCategorie" @keyup="search('all')" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="from-group">
                    <label>Code panne</label>
                    <select @change="search('all')" class="form-control" v-model="qCodePanne">
                      <option value="" selected>Vide</option>
                      <option
                        v-for="cp in allCodePanne"
                        :key="cp.id"
                        :value="cp.id"
                      >
                        {{ cp.code }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <label for="code_machine">Code machine</label>
                  <input type="text" v-model="qCodeMachine" @keyup="search('all')" class="form-control">
                </div>
                 <div class="col">
                  <div class="from-group">
                    <label>Zone</label>
                    <select @change="search('all')" class="form-control" v-model="qZone">
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
              </div>
            </div>
          </template>
          <div class="card-header">
            <h3 class="card-title">Liste de tous les historiques</h3>
            <div class="card-tools">
              <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Num Bt</th>
                  <th>Jour</th>
                  <th>Heure de demande</th>
                  <th>Heure de début</th>
                  <th>Heure de fin</th>
                  <th>Heure d'attente</th>
                  <th>Heure d'arret</th>
                  <th>Description</th>
                  <th>Code machine</th>
                  <th>Designation</th>
                  <th>Emplacement</th>
                  <th>NSerie</th>
                  <th>Code panne</th>
                  <th>Designation CP</th>
                  <th>Type Travaille</th>
                  <th>Zone</th>
                  <th>BT cloturé</th>
                  <th>Travaille éffectué</th>
                  <th>Pièce de rechange</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="historique in historiques.data" :key="historique.id">
                  <td>{{ historique.id }}</td>
                  <td>{{ historique.jour }}</td>
                  <td>{{ historique.heure_demande }}</td>
                  <td>{{ historique.heure_debut }}</td>
                  <td>{{ historique.heure_fin }}</td>
                  <td>{{ historique.heure_attente }}</td>
                  <td>{{ historique.heure_arret }}</td>
                  <td>{{ historique.description_demande }}</td>
                  <td>{{ historique.code_equip }}</td>
                  <td>{{ historique.designation }}</td>
                  <td>{{ historique.emplacement }}</td>
                  <td>{{ historique.n_serie }}</td>
                  <td>{{ historique.codePanne }}</td>
                  <td>{{ historique.codePanneDesignation }}</td>
                  <td>{{ historique.type_travaille }}</td>
                  <td>{{ historique.zone }}</td>
                  <td>{{ historique.appelle }}</td>
                  <td>{{ historique.travaille }}</td>
                  <td>{{ historique.piece_rechange }}</td>
                </tr>
              </tbody>
            </table>
            <pagination
              class="m-auto"
              :data="historiques"
              @pagination-change-page="getResults"
              :limit="5" 
            ></pagination>
          </div>
          <!-- /.card-body -->
        </div>
      </template>
    </div>
  </div>
</template>
<script>
import DeleteHist from '../DeleteHistoriques';
import AddHistorique from './AddHistorique';
import EditHistorique from './EditHistorique';
export default {
  components:{
    AddHistorique,
    EditHistorique,
    DeleteHist,
  },
  props:['user'],
  data() {
    return {
      histsHotline: {},
      historiques: {},
      historiqueToEdit: "",
      tech: "",
      techs: "",
      hidden: "false",
      hiddenS: "true",
      qNumBt: "",
      qZone: "",
      qDateDebut: "",
      qDateFin:"",
      qAppelle: "",
      qCodeCategorie:"",
      qCodeMachine:"",
      qTech: "",
      qCodePanne:"",
      allCodePanne:'',
      q: new FormData(),
      id: "",
      histSertissage: "",
      loading:true,
      equipements:''
    };
  },
  methods: {
    toast(value) {
      this.$swal.fire({
        icon: "success",
        title: value,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
      });
    },
    
    getResults(page = 1) {
      axios.post("/historiques/liste?page=" + page)
      .then((response) => {
        this.historiques = response.data;
      })
      .catch((error) => console.log(error));
    },
    getResultsForHotline(page = 1) {
      this.q.append("type",'HOTLINE');
      axios.post("/historiques/liste?page=" + page,this.q)
      .then((response) => {
        this.histsHotline = response.data;
      })
      .catch((error) => console.log(error));
    },
    
    search(type) {
      this.q.append("num_bt", this.qNumBt);
      this.q.append("zone", this.qZone);
      this.q.append("date_debut", this.qDateDebut);
      this.q.append("date_fin", this.qDateFin);
      this.q.append("appelle", this.qAppelle);
      this.q.append("code_categorie", this.qCodeCategorie)
      this.q.append("code_equip", this.qCodeMachine)
      this.q.append("tech_id", this.qTech);
      this.q.append("code_panne", this.qCodePanne)
      this.q.append("type",type);
      axios.post("/historiques/liste", this.q)
      .then(response => {
        this.histsHotline = response.data;
        this.historiques = response.data;
        this.loading = false; 
      })
      .catch(error => console.log(error))
    },
    showSearch(){
      if(this.hiddenS == 'true'){
        this.hiddenS = 'false';
        return 1;
      }
      if(this.hiddenS == 'false'){
        this.hiddenS = 'true';
        return 1;
      }
    },
    viewAll(status) {
      this.hidden = status;
      if(status == 'false'){
        this.search('HOTLINE');
      }else{
        this.search('all');
      }
    },
    refreshForHotline(histsHotline) {
      this.histsHotline = histsHotline.data;
    },
    refresh(historiques) {
      this.historiques = historiques.data;
    },
    setId(id) {
      this.id = id;
    },
    refreshDeleted(historiques) {
      this.histsHotline = historiques.data;
      var value = "Historique a été supprimé avec succées";
      this.toast(value);
    },
    refreshEdited(historiques) {
      var value = "Historique a été modifié avec succées";
      this.histsHotline = historiques.data;
      this.toast(value);
    },
    refreshAdded(historiques) {
      var value = "Historique a été ajouté avec succées";
      this.histsHotline = historiques.data;
      this.toast(value);
    },
    async getHistorique(id) {
      await axios.get("/historiques/edit/" + id)
      .then((response) => (this.historiqueToEdit = response.data))
      .catch((error) => console.log(error));
      await axios.get("/historiques/techniciens/" + this.historiqueToEdit[0].zone)
      .then((response) => (this.tech = response.data))
      .catch((error) => console.log(error));
      await axios.get('/historiques/equipement/zone/' + this.historiqueToEdit[0].zone)
      .then(response => this.equipements =  response.data)
      .catch(error => console.log(error));
    },
  },
  created() {
    if(this.user.role != 'HOTLINE'){
      this.$router.push('/');
    }
    this.search('HOTLINE');
    axios.get("/historiques/techs")
    .then((response) => (this.techs = response.data))
    .catch((error) => console.log(error));
    axios.get('/code_pannes/liste-all')
    .then(response => {
      this.allCodePanne = response.data
    })
  },
};
</script>