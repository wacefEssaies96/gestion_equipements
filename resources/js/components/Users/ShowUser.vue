<template>
  <!-- Modal -->
  <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="showModalTitle">Profile&nbsp;&nbsp;
                  <i v-if="userShow.etat==1" class="fas fa-dot-circle text-success" title="Compte actif"></i>
                  <i v-else class="fas fa-dot-circle text-secondary" title="Compte désactivé"></i>
                </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Les coordonnées personnelles</h3>
              </div>
              <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5>Code</h5>
                            <p class="text-secondary">{{userShow.code}}</p>
                        </div>
                         <div class="col-6">
                            <h5>Nom</h5>
                            <p class="text-secondary">{{userShow.nom}}</p>
                        </div>
                        <div class="col-6">
                            <h5>Prénom</h5>
                            <p class="text-secondary">{{userShow.prenom}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>Email</h5>
                            <p class="text-secondary">{{userShow.email}}</p>
                        </div>
                        <div class="col-6">
                            <h5>Téléphone</h5>
                            <p class="text-secondary">{{userShow.tel}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>Role</h5>
                            <p class="text-secondary">{{userShow.role}}</p>
                        </div>
                     
                    </div>
                <template v-if="userShow.role == 'TECHNICIEN'">
                    <div class="row">
                           <div class="col-6">
                            <h5>zone</h5>
                            <p class="text-secondary">{{userShow.zone}}</p>
                        </div>
                        <div class="col-6">
                            <h5>Qualification</h5>
                            <p class="text-secondary">{{userShow.qualification}}</p>
                        </div>
  
                    </div>
                </template>   
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
      props: ['userShow']
    }
</script>