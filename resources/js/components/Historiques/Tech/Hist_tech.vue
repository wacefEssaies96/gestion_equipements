<template>
<div>
  <template v-if="hidden == 'false'"><button @click="viewAll('true')">Afficher tous les historiques</button></template>
  <template v-if="hidden == 'true'"><button @click="viewAll('false')">Afficher les historiques du technicien</button></template>
    <template v-if="hidden == 'false'">
      <div class="card">
      <div class="card-header">
        <h3 class="card-title">Liste de tous les historiques du technicien</h3>
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
            <tr v-for="historique in historiques" :key="historique.id">
              <template v-if="historique.tech_id == techId && historique.valide == false">
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
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getEquipements(historique.zone);">
                  Modifier
                  </button>
                  <edit-hist-tech 
                    v-bind:id="historique"
                    v-bind:equipements="equipements"
                    v-bind:codePannes="codePannes" 
                    @historique-updated="refresh">
                  </edit-hist-tech> 
                </td>
              </template>
            </tr> 
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
      </div>
    </template>
    <template v-if="hidden == 'true'">
      <div class="card">
      <div class="card-header">
        <h3 class="card-title">Liste de tous les historiques</h3>
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
            <tr v-for="historique in historiques" :key="historique.id">
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
      </div>
      <!-- /.card-body -->
      </div>
    </template>
</div>
</template>
<script>
  export default {
    data(){
      return{
        historiques: {},
        id: '',
        techId: '',
        equipements: '',
        codePannes: '',
        hidden: 'false'
      }
    },
    created(){
      axios.post("http://localhost:8000/historiques/liste")
      .then(response => this.historiques = response.data)
      .catch(error => console.log(error))
      axios.get("http://localhost:8000/historiques/ht")
      .then(response => this.techId = response.data)
      .catch(error => console.log(error))
    },
    methods:{
      viewAll(status){
        this.hidden = status;
      },
      refresh(historiques){
        this.historiques = historiques.data; 
      },
      getEquipements(zone){
        axios.get('http://localhost:8000/historiques/tech/zone/' + zone)
        .then(response =>this.equipements =  response.data)
        .catch(error => console.log(error));
        this.getCodePannes(zone);
      },
      getCodePannes(zone){
        axios.get("http://localhost:8000/historiques/tech/equip/"+zone)
        .then(response => this.codePannes = response.data)
        .catch(error => console.log(error))
      },
    }
  }
</script>