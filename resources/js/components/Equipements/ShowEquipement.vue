<template>
  <!-- Modal -->
  <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="showModalTitle">Equipement</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Les coordonnées d'equipement</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <img :src="equipementShow.image" alt="Photo Aperçu de l'équipement" style="width:100px; height:100px">
                  </div>
                  <div class="col-9">
                    <div class="row">
                        <div class="col-6">
                            <h5>Nom</h5>
                            <p class="text-secondary">{{equipementShow.nom}}</p>
                        </div>
                        <div class="col-6">
                            <h5>Code</h5>
                            <p class="text-secondary">{{equipementShow.code}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>Designation</h5>
                            <p class="text-secondary">{{equipementShow.designation}}</p>
                        </div>
                        <div class="col-6">
                            <h5>N°serie</h5>
                            <p class="text-secondary">{{equipementShow.n_serie}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>zone</h5>
                            <p class="text-secondary">{{equipementShow.zone}}</p>
                        </div>
                     
                    </div>
                  </div>
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
import { } from 'vuelidate/lib/validators';
    export default {
      props: ['equipementShow']
    }
</script>