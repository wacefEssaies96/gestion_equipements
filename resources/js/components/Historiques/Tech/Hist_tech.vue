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
  <template v-if="hidden == 'false'"><button @click="viewAll('true')">Afficher tous les historiques</button></template>
  <template v-if="hidden == 'true'"><button @click="viewAll('false')">Afficher les historiques du technicien</button></template>
    <template v-if="hidden == 'false'">
      <div class="card m-lg-4">
      <div class="card-header">
        <h3 class="card-title">Liste de tous les historiques du technicien</h3>
        <div class="card-tools">
    
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Num Bt</th>
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
              <th>Type Travaille</th>
              <th>Zone</th>
              <th>BT cloturé</th>
              <th>Travaille éffectué</th>
              <th>Pièce de rechange</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="historique in histsTech.data" :key="historique.id">
              <td>{{ historique.id }}</td>
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
              <td>{{ historique.type_travaille }}</td>
              <td>{{ historique.zone }}</td>
              <td>{{ historique.appelle }}</td>
              <td>{{ historique.travaille }}</td>
              <td>{{ historique.piece_rechange }}</td>
                <td> 
                  <template v-if="historique.valide == false">
                    <button type="button" class="btn btn-primary" @click="confirmAppelle(historique.id);">
                    Confirmer
                    </button>
                  </template>
                  <template v-if="historique.valide == true">
                    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getEquipements(historique.zone);">
                    <i class="fas fa-edit" title="Remplir"></i>
                    </button>
                  </template>
                  <EditHistTech 
                    v-bind:id="historique"
                    v-bind:equipements="equipements"
                    v-bind:codePannes="codePannes" 
                    @historique-updated="refreshEdited">
                  </EditHistTech> 
                </td>
            </tr> 
          </tbody>
        </table>
        <pagination :limit="5"  class="m-auto" :data="histsTech" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
      </div>
    </template>
    <template v-if="hidden == 'true'">
      <div class="card m-lg-4">
      <div class="card-header">
        <h3 class="card-title">Liste de tous les historiques</h3>
        <div class="card-tools">
          
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Num Bt</th>
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
              <td>{{ historique.type_travaille }}</td>
              <td>{{ historique.zone }}</td>
              <td>{{ historique.appelle }}</td>
              <td>{{ historique.travaille }}</td>
              <td>{{ historique.piece_rechange }}</td>
            </tr> 
          </tbody>
        </table>
        <pagination :limit="5"  class="m-auto" :data="historiques" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
      </div>
    </template>
</div>
</template>
<script>
  import EditHistTech from './EditHistTech';
  export default {
    components:{
      EditHistTech,
    },
    props:['user'],
    data(){
      return{
        historiques: {},
        histsTech: {},
        id: '',
        equipements: '',
        codePannes: '',
        hidden: 'false',
      }
    },
    created(){
      if(this.user.role != 'TECHNICIEN'){
        this.$router.push('/');
      }
      axios.post("/historiques/liste")
      .then(response => this.historiques = response.data)
      .catch(error => console.log(error))
      axios.get("/hist/tech/liste")
      .then(response => this.histsTech = response.data)
      .catch(error => console.log(error))
    },
    methods:{
      confirmAppelle(id){
        axios.get("/histtech/confirmAppelle/" + id)
        .then(response => {
            if(response.data == 'erreur'){
              this.toast("Une erreur a été produite !"); 
            }
            else{
              axios.post("/historiques/liste")
              .then(response => this.historiques = response.data)
              .catch(error => console.log(error))
              this.histsTech = response.data;
              this.toast("Vous avez confirmé L'appelle !") ;
            }
        })
        .catch(error => console.log(error));


      },
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
      refreshEdited(historiques) {
        var value = "Historique a été modifié avec succées";
        this.histsTech = historiques.data;
        this.toast(value);
      },
      getResults(page = 1) {
        axios.post('/historiques/liste?page=' + page)
        .then(response => {
          this.historiques = response.data;
        })
        .catch(error => console.log(error));
      },
      viewAll(status){
        this.hidden = status;
      },
      refresh(historiques){
        this.historiques = historiques.data; 
      },
      getEquipements(zone){
        axios.get('/historiques/equipement/zone/' + zone)
        .then(response =>this.equipements =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        axios.get("/historiques/code-panne/"+zone)
        .then(response => this.codePannes = response.data)
        .catch(error => console.log(error))
      },
    }
  }
</script>