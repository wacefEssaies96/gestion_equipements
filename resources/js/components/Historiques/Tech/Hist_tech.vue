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
              <th>Heure d'arret'</th>
              <th>Jour</th>
              <th>Zone</th>
              <th>Appelle</th>
              <th>Travaille éffectué</th>
              <th>Pièce de rechange</th>
              <th>Commentaire</th>
              <th>Action</th>
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
                <td>{{ historique.jour }}</td>
                <td>{{ historique.zone }}</td>
                <td>{{ historique.appelle }}</td>
                <td>{{ historique.travaille }}</td>
                <td>{{ historique.piece_rechange }}</td>
                <td>{{ historique.commentaire }}</td>
                <td> 
                  <template v-if="historique.heure_debut == null">
                    <button type="button" class="btn btn-primary" @click="confirmAppelle(historique.id);">
                    Confirmer
                    </button>
                  </template>
                  <template v-if="historique.heure_debut != null">
                    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getEquipements(historique.zone);">
                    <i class="far fa-file-edit" title="Remplir"/>
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
                <th>Heure d'arret'</th>
                <th>Jour</th>
                <th>Zone</th>
                <th>Appelle</th>
                <th>Travaille éffectué</th>
                <th>Pièce de rechange</th>
                <th>Commentaire</th>
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
              <td>{{ historique.jour }}</td>
              <td>{{ historique.zone }}</td>
              <td>{{ historique.appelle }}</td>
              <td>{{ historique.travaille }}</td>
              <td>{{ historique.piece_rechange }}</td>
              <td>{{ historique.commentaire }}</td>
            </tr> 
          </tbody>
        </table>
        <pagination class="m-auto" :data="historiques" @pagination-change-page="getResults"></pagination>
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
        baseUrl:process.env.MIX_URL,
      }
    },
    created(){
      if(this.user.role != 'TECHNICIEN'){
        this.$router.push('/');
      }
      axios.post(this.baseUrl+"/historiques/liste")
      .then(response => this.historiques = response.data)
      .catch(error => console.log(error))
      axios.get(this.baseUrl+"/hist/tech/liste")
      .then(response => this.histsTech = response.data)
      .catch(error => console.log(error))

    },
    methods:{
      confirmAppelle(id){
        axios.get(this.baseUrl+"/histtech/confirmAppelle/" + id)
        .then(response => {
            if(response.data == 'erreur'){
              this.toast("Une erreur a été produite !"); 
            }
            else{
              axios.post(this.baseUrl+"/historiques/liste")
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
        axios.post(this.baseUrl+'/historiques/liste?page=' + page)
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
        axios.get(this.baseUrl+'/historiques/equipement/zone/' + zone)
        .then(response =>this.equipements =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        axios.get(this.baseUrl+"/historiques/code-panne/"+zone)
        .then(response => this.codePannes = response.data)
        .catch(error => console.log(error))
      },
    }
  }
</script>