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
                <label>Num Bt</label>
                <input @keyup="search" type="text" v-model="qNumBt" class="form-control" placeholder="Num Bt">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Date</label>
                <input @keyup="search" type="date" v-model="qJour" class="form-control">
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
                <label>Technicien</label>
                <select @change="search" class="form-control" v-model="qTech">
                  <option value="" selected>Vide</option>
                  <option v-for="tech in techs" :key="tech.id" :value="tech.id">{{ tech.nom }} {{ tech.prenom }}</option>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
              <label>Appelle</label>
              <select @change="search" class="form-control" v-model="qAppelle">
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
  <add-hist-admin @historique-added="refreshAdded"></add-hist-admin>  
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
            <td> 
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getHistorique(historique.id);">
              Modifier
              </button>
              <button @click="setId(historique.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-danger">Supprimer</button>
              <delete-historique v-bind:id="id" @historique-deleted="refreshDeleted"></delete-historique>
              <edit-hist-admin 
                v-bind:historiqueToEdit="historiqueToEdit"
                v-bind:tech="tech"
                v-bind:equipements="equipements"
                v-bind:codePannes="codePannes" 
                @historique-updated="refreshEdited">
              </edit-hist-admin> 
            </td>
          </tr> 
        </tbody>
      </table>
      <pagination class="m-auto" :data="historiques" @pagination-change-page="getResults"></pagination>
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
        historiques: {},
        historiqueToEdit: '',
        tech: '',
        techs: '',
        qNumBt: '',
        qZone: '',
        qJour: '',
        qAppelle: '',
        qTech: '',
        q: new FormData(),
        id: '',
        techId: '',
        equipements: '',
        codePannes: '',
        hidden:'true',
      }
    },
    created(){
      if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
      axios.post("http://localhost:8000/historiques/liste")
      .then(response => this.historiques = response.data)
      .catch(error => console.log(error))
      axios.get("http://localhost:8000/historiques/techs")
      .then(response => this.techs = response.data)
      .catch(error => console.log(error))
    },
    methods:{
      getResults(page = 1) {
        axios.post('http://localhost:8000/historiques/liste?page=' + page)
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
        this.q.append('num_bt', this.qNumBt);
        this.q.append('zone', this.qZone);
        this.q.append('jour', this.qJour);
        this.q.append('appelle', this.qAppelle);
        this.q.append('tech_id', this.qTech);

        axios.post("http://localhost:8000/historiques/liste", this.q)
        .then(response => this.historiques = response.data)
        .catch(error => console.log(error))

      },
      getHistorique(id){
        axios.get('http://localhost:8000/historiques/edit/' + id)
        .then(response => this.historiqueToEdit = response.data)
        .catch(error => console.log(error));  
        setTimeout(() => this.getTech(this.historiqueToEdit[0].zone), 2000);
        setTimeout(() => this.getEquipements(this.historiqueToEdit[0].zone), 2000);
      },
      getTech(zone){
        axios.get("http://localhost:8000/historiques/techniciens/" + zone)
        .then(response => this.tech = (response.data)) 
        .catch(error => console.log(error))
      },        
      getEquipements(zone){
        axios.get('http://localhost:8000/historiques/equipement/zone/' + zone)
        .then(response =>this.equipements =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        axios.get("http://localhost:8000/historiques/code-panne/"+zone)
        .then(response => this.codePannes = response.data)
        .catch(error => console.log(error))
      },
    }
  }
</script>