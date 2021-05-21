<template>
  <!-- Modal -->
  <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div :class="{'overlay':l}">
            <i :class="{'fas fa-2x fa-sync fa-spin':l}"></i>
          </div>
          <div class="modal-header">
                <h5 class="modal-title" id="showModalTitle">Equipement</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <ViewPdf @closed="refreshDoc" v-bind:path="document.document"></ViewPdf>
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Les informations de l'equipement {{equipementShow.designation}}</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-8">
                    <img :src="equipementShow.image" alt="équipement" style="width:100%; height:95%">
                  </div>
                  <div class="col-4">
                    <h5>Code</h5>
                    <p class="text-secondary">{{equipementShow.code}}</p>
                    <h5>Designation</h5>
                    <p class="text-secondary">{{equipementShow.designation}}</p>
                    <h5>Emplacement</h5>
                    <p class="text-secondary">{{equipementShow.emplacement}}</p>
                    <h5>Code catégorie</h5>
                    <p class="text-secondary">{{ equipementShow.code_categorie }}</p>
                    <h5>Date d'acquisition</h5>
                    <p class="text-secondary">{{ equipementShow.date_acq }}</p>
                    <h5>N°serie</h5>
                    <p class="text-secondary">{{equipementShow.n_serie}}</p>
                    <h5>Constructeur</h5>
                    <p class="text-secondary">{{equipementShow.constructeur}}</p>
                    <h5>Etat</h5>
                    <p class="text-secondary">{{equipementShow.etat}}</p>
                    <h5>zone</h5>
                    <p class="text-secondary">{{equipementShow.zone}}</p>
                  </div>
                </div>
                <div class="row pt-2">
                  <button @click="getDocuments(equipementShow.id,'ins_c')" class="btn btn-outline-info">
                    <i class="fas fa-file-pdf"></i> Instruction 1ér niveau
                  </button>
                  <button @click="getDocuments(equipementShow.id,'ins_p')" class="btn btn-outline-info">
                    <i class="fas fa-file-pdf"></i> Instruction préventive
                  </button>
                  <button @click="getDocuments(equipementShow.id,'dossier_technique')" class="btn btn-outline-info">
                    <i class="fas fa-file-pdf"></i> Dossier technique
                  </button>
                  <button @click="getDocuments(equipementShow.id,'liste_pr')" class="btn btn-outline-info">
                    <i class="fas fa-file-pdf"></i> Liste PR
                  </button>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import ViewPdf from './ViewPdfFile.vue';
  export default {
    props: ['equipementShow'],
    components:{
      ViewPdf
    },
    data(){
      return{
        document:'',
        l:false,
      }
    },
    methods:{
      getDocuments(id,type){
        this.l = true;
        axios.get('/equipements/document/'+id+'/'+type)
        .then(response => {
          this.document = response.data
          if(response.data != 'introuvable'){
            document.getElementById('openPdf').click();
          }else{
            this.$swal.fire({
              icon: 'warning',
              title: "Aucun document disponible !",
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5000
            });
          }
          this.l = false;
        })
        .catch(error => {
          this.l = false;
          console.log(error);
        });
      },
      refreshDoc(){
        this.document = '';
      },
    }
  }
</script>