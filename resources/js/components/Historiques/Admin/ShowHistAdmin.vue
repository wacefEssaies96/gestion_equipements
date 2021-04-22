<template>
  <!-- Modal -->
  <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
                <h3 class="card-title">Les coordonnées d'intervention
                &nbsp;&nbsp;
                  <i v-if="historiqueShow.appelle=='Cloturé'" class="fas fa-dot-circle text-success" title="Appelle cloturé"></i>
                  <i v-else class="fas fa-dot-circle text-danger" title="Appelle non cloturé"></i>
                </h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <h5>Jour</h5>
                    <p class="text-secondary">{{historiqueShow.jour }}</p>
                </div>
                <div class="col-6">
                    <h5>heure_demande</h5>
                    <p class="text-secondary">{{historiqueShow.heure_demande}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>heure_debut</h5>
                    <p class="text-secondary">{{historiqueShow.heure_debut}}</p>
                </div>
                <div class="col-6">
                    <h5>heure_attente</h5>
                    <p class="text-secondary">{{historiqueShow.heure_attente}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>zone</h5>
                    <p class="text-secondary">{{historiqueShow.zone}}</p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>heure_fin</h5>
                    <p class="text-secondary">{{historiqueShow.heure_fin}}</p>
                </div>
                <div class="col-6">
                    <h5>heure_arret</h5>
                    <p class="text-secondary">{{historiqueShow.heure_arret}}</p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>travaille</h5>
                    <p class="text-secondary">{{historiqueShow.travaille}}</p>
                </div>
                <div class="col-6">
                    <h5>piece_rechange</h5>
                    <p class="text-secondary">{{historiqueShow.piece_rechange}}</p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>commentaire</h5>
                    <p class="text-secondary">{{historiqueShow.commentaire}}</p>
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
      props: ['historiqueShow']
    }
</script>