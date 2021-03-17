<template>
  <div>
    <template v-if="hidden == 'false'"><button @click="viewAll('true')">Afficher tous les historiques</button></template>
    <template v-if="hidden == 'true'"><button @click="viewAll('false')">Afficher les historiques du hotline</button></template>
      <template v-if="hidden == 'false'">
        <div class="card" id="histhotline">
                    <div class="card">
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
            <!-- <div class="input-group input-group-sm" style="width: 100%;"> -->
              
            <!-- </div> -->
          </div>
          <div class="card-header">
            <h3 class="card-title">Liste de tous historiques du hotline</h3>
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
        <add-historique @historique-added="refresh"></add-historique> 
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
                  <template v-if="historique.hotline_id == hotlineId">
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
                    <button type="button" class="btn btn-danger" @click="deleteHistorique(historique.id)">Supprimer</button>
                    <edit-historique 
                      v-bind:historiqueToEdit="historiqueToEdit"
                      v-bind:tech="tech" 
                      @historique-updated="refresh">
                    </edit-historique> 
                  </td>
                  </template>
                </tr> 
              </tbody>
              <p hidden id="z">{{historiqueToEdit.zone}}</p>
            </table>
            
          </div>

          <!-- /.card-body -->
        </div>
      </template>
      <template v-if="hidden == 'true'">
        <div class="card" id="histall">
          <div class="card">
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
            <!-- <div class="input-group input-group-sm" style="width: 100%;"> -->
              
            <!-- </div> -->
          </div>
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
                <td>{{ historique.jour}}</td>
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
        hotlineId : '',
        historiqueToEdit: '',
        tech: '',
        techs: '',
        hidden: 'false',
        qNumBt: '',
        qZone: '',
        qJour: '',
        qAppelle: '',
        qTech: '',
        q: new FormData(),
      }
    },
    methods:{
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
      viewAll(status){
        this.hidden = status;
      },
      refresh(historiques){
        this.historiques = historiques.data; 
      },
      getHistorique(id){
        axios.get('http://localhost:8000/historiques/edit/' + id)
        .then(response => this.historiqueToEdit = response.data)
        .catch(error => console.log(error));
        setTimeout(() => this.getTech(), 2000);
      },
      getTech(){
        var zone = document.getElementById('z').innerText;
        axios.get("http://localhost:8000/historiques/tech/"+zone)
        .then(response => this.tech = (response.data)) 
        .catch(error => console.log(error))
      },        
      deleteHistorique(id){
        axios.delete('http://localhost:8000/historiques/' + id)
        .then(response => this.historiques = response.data)
        .catch(error => console.log(error));               
      },
    },
    created(){
      axios.post("http://localhost:8000/historiques/liste")
      .then(response => this.historiques = response.data)
      .catch(error => console.log(error))
      axios.get("http://localhost:8000/historiques/ht")
      .then(response => this.hotlineId = response.data)
      .catch(error => console.log(error))
      axios.get("http://localhost:8000/historiques/techs")
      .then(response => this.techs = response.data)
      .catch(error => console.log(error))
    },
  }
</script>