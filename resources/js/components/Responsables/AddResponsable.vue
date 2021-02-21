<template>
<div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Ajouter un nouveau responsable
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Ajouter un nouveau responsable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Formulaire</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" class="form-control" placeholder="Nom" v-model="nom">
                  </div>
                  <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" id="prenom" class="form-control" placeholder="prenom" v-model="prenom">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="email" v-model="email">
                  </div>
                  <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="pseudo" id="pseudo" class="form-control" placeholder="pseudo" v-model="pseudo">
                  </div>
                  <div class="form-group">
                    <label for="tel">téléphone</label>
                    <input type="tel" id="tel" class="form-control" placeholder="tel" v-model="tel">
                  </div>
                  <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="password" class="form-control" placeholder="password" v-model="password">
                  </div>
                  <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" id="role" v-model="role">
                          <option>Admin</option>
                          <option>Responsable</option>
                          <option>Technicien</option>
                        </select>
                      </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" >
                        <label class="custom-file-label" for="exampleInputFile">Choisir une image</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" @click="responsableStore" data-dismiss="modal">Confirm</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
  export default {
         data(){
            return{
                nom: '',
                prenom: '',
                email: '',
                tel: '',
                pseudo: '',
                password: '',
                role: '',
            }
        },
        methods:{
            responsableStore(){
                axios.post('http://localhost:8000/responsables',{
                    nom: this.nom,
                    prenom: this.prenom,
                    email: this.email,
                    tel: this.tel,
                    pseudo: this.pseudo,
                    password: this.password,
                })
                .then(response => this.$emit('responsable-added',response))
                .catch(error => console.log(error));
            }
        }
  }
</script>
