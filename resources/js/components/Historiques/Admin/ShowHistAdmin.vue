<template>
  <!-- Modal -->
  <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="showModalTitle">Historique</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Les coordonnées d'historique
                &nbsp;&nbsp;
                  <i v-if="historiqueShow.appelle==Cloturé" class="fas fa-dot-circle text-success" title="Appelle cloturé"></i>
                  <i v-else class="fas fa-dot-circle text-danger" title="Appelle non cloturé"></i>
                </h3>
              </div>
              <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5>Jour</h5>
                            <p class="text-secondary">{{historiqueShow.jour}}</p>
                        </div>
                        <div class="col-6">
                            <h5>heure_demande</h5>
                            <p class="text-secondary">{{equipementShow.heure_demande}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>heure_debut</h5>
                            <p class="text-secondary">{{equipementShow.heure_debut}}</p>
                        </div>
                        <div class="col-6">
                            <h5>heure_attente</h5>
                            <p class="text-secondary">{{equipementShow.heure_attente}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5>zone</h5>
                            <p class="text-secondary">{{equipementShow.zone}}</p>
                        </div>
                     
                    </div>
                    <!-- $table->time('heure_attente')->nullable();
            $table->time('heure_arret')->nullable();
            //hotline
           
            $table->enum('appelle',['Cloturé','Non cloturé'])->nullable();
           
            
            $table->enum('zone', ['Sertissage', 'Assemblage','Coupe','Préparation','Controle éléctrique'])->nullable();
            $table->bigInteger('tech_id')->unsigned();
            //technicien
            
            $table->time('heure_fin')->nullable();
            $table->string('travaille')->nullable();
            $table->string('piece_rechange')->nullable();
            $table->string('commentaire')->nullable();
            $table->bigInteger('code_equip')->unsigned()->nullable();

            $table->bigInteger('hotline_id')->unsigned();
            $table->foreign('hotline_id')->references('id')->on('users');
            $table->foreign('tech_id')->references('id')->on('users');
            $table->foreign('code_equip')->references('id')->on('equipements'); -->
                 
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