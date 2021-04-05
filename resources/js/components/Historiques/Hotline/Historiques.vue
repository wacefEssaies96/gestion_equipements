<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Historiques</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Historiques</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div>
      <template v-if="hidden == 'false'"
        ><button @click="viewAll('true')">
          Afficher tous les historiques
        </button></template
      >
      <template v-if="hidden == 'true'"
        ><button @click="viewAll('false')">
          Afficher les historiques du hotline
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
                    <label>Num Bt</label>
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
                    <label>Date</label>
                    <input
                      @keyup="search"
                      type="date"
                      v-model="qJour"
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
                  <div class="form-group">
                    <label>Appelle</label>
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
              </div>
            </div>
          </div>
          </template>
          <div class="card-header">
            <h3 class="card-title">Liste de tous historiques du hotline</h3>
            <div class="card-tools">
              <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <add-historique @historique-added="refreshAdded"></add-historique>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Num Bt</th>
                  <th>Heure de demande</th>
                  <th>Jour</th>
                  <th>Zone</th>
                  <th>Appelle</th>
                  <th>Heure de début</th>
                  <th>Heure de fin</th>
                  <th>Travaille éffectué</th>
                  <th>Pièce de rechange</th>
                  <th>Commentaire</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="histHotline in histsHotline.data"
                  :key="histHotline.id"
                >
                  <td>{{ histHotline.num_bt }}</td>
                  <td>{{ histHotline.heure_demande }}</td>
                  <td>{{ histHotline.jour }}</td>
                  <td>{{ histHotline.zone }}</td>
                  <td>{{ histHotline.appelle }}</td>
                  <td>{{ histHotline.heure_debut }}</td>
                  <td>{{ histHotline.heure_fin }}</td>
                  <td>{{ histHotline.travaille }}</td>
                  <td>{{ histHotline.piece_rechange }}</td>
                  <td>{{ histHotline.commentaire }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-primary"
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
                      class="btn btn-danger"
                    >
                      <i class="fas fa-trash-alt" title="Supprimer"/>
                    </button>
                    <delete-historique
                      v-bind:id="id"
                      @historique-deleted="refreshDeleted"
                    ></delete-historique>
                    <edit-historique
                      v-bind:historiqueToEdit="historiqueToEdit"
                      v-bind:tech="tech"
                      @historique-updated="refreshEdited"
                    >
                    </edit-historique>
                  </td>
                </tr>
              </tbody>
            </table>
            <pagination
              class="m-auto"
              :data="histsHotline"
              @pagination-change-page="getResultsForHotline"
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
                      <label>Date</label>
                      <input
                        @keyup="search"
                        type="date"
                        v-model="qJour"
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
                    <div class="form-group">
                      <label>Appelle</label>
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
                  <th>Heure de demande</th>
                  <th>Jour</th>
                  <th>Zone</th>
                  <th>Appelle</th>
                  <th>Heure de début</th>
                  <th>Heure de fin</th>
                  <th>Travaille éffectué</th>
                  <th>Pièce de rechange</th>
                  <th>Commentaire</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="historique in historiques.data" :key="historique.id">
                  <td>{{ historique.num_bt }}</td>
                  <td>{{ historique.heure_demande }}</td>
                  <td>{{ historique.jour }}</td>
                  <td>{{ historique.zone }}</td>
                  <td>{{ historique.appelle }}</td>
                  <td>{{ historique.heure_debut }}</td>
                  <td>{{ historique.heure_fin }}</td>
                  <td>{{ historique.travaille }}</td>
                  <td>{{ historique.piece_rechange }}</td>
                  <td>{{ historique.commentaire }}</td>
                </tr>
              </tbody>
            </table>
            <pagination
              class="m-auto"
              :data="historiques"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
          <!-- /.card-body -->
        </div>
      </template>
    </div>
  </div>
</template>
<script>
import AddHistorique from './AddHistorique';
import EditHistorique from './EditHistorique';   
export default {
   components:{
         AddHistorique,
         EditHistorique,
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
      qJour: "",
      qAppelle: "",
      qTech: "",
      q: new FormData(),
      id: "",
      baseUrl:process.env.MIX_URL,
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
    getResults(page = 1) {
      axios
        .post(this.baseUrl+"/historiques/liste?page=" + page)
        .then((response) => {
          this.historiques = response.data;
        })
        .catch((error) => console.log(error));
    },
    getResultsForHotline(page = 1) {
      axios
        .get(this.baseUrl+"/historiques/hotline?page=" + page)
        .then((response) => {
          this.histsHotline = response.data;
        })
        .catch((error) => console.log(error));
    },
    search() {
      this.q.append("num_bt", this.qNumBt);
      this.q.append("zone", this.qZone);
      this.q.append("jour", this.qJour);
      this.q.append("appelle", this.qAppelle);
      this.q.append("tech_id", this.qTech);

      axios
        .post(this.baseUrl+"/historiques/liste", this.q)
        .then((response) => (this.historiques = response.data))
        .catch((error) => console.log(error));
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
    },
    refreshForHotline(histsHotline) {
      this.histsHotline = histsHotline.data;
    },
    refresh(historiques) {
      this.historiques = historiques.data;
    },
    getHistorique(id) {
      axios
        .get(this.baseUrl+"/historiques/edit/" + id)
        .then((response) => (this.historiqueToEdit = response.data))
        .catch((error) => console.log(error));
      setTimeout(() => this.getTech(this.historiqueToEdit[0].zone), 2000);
    },
    getTech(zone) {
      axios
        .get(this.baseUrl+"/historiques/techniciens/" + zone)
        .then((response) => (this.tech = response.data))
        .catch((error) => console.log(error));
    },
  },
  created() {
    if(this.user.role != 'HOTLINE'){
          this.$router.push('/');
        }
    axios
      .post(this.baseUrl+"/historiques/liste")
      .then((response) => (this.historiques = response.data))
      .catch((error) => console.log(error));
    axios
      .get(this.baseUrl+"/historiques/techs")
      .then((response) => (this.techs = response.data))
      .catch((error) => console.log(error));
    axios
      .get(this.baseUrl+"/historiques/hotline")
      .then((response) => (this.histsHotline = response.data))
      .catch((error) => console.log(error));
  },
};
</script>