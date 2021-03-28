<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Hotlines</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Hotlines</li>
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
                    <label>Nom</label>
                    <input @keyup="search" type="text" v-model="qNom" class="form-control" placeholder="Nom">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label>Prenom</label>
                    <input @keyup="search" type="text" v-model="qPrenom" class="form-control" placeholder="Prenom">
                  </div>
                </div>
                <div class="col">
                  <div class="from-group">
                    <label>Pseudo</label>
                    <input @keyup="search" type="text" v-model="qPseudo" class="form-control" placeholder="Pseudo">
                  </div>
                </div>
                <div class="col">
                  <div class="from-group">
                    <label>Tel</label>
                    <input @keyup="search" type="text" v-model="qTel" class="form-control" placeholder="12345678">
                  </div>
                </div>
                <div class="col">
                  <div class="from-group">
                    <label>Email</label>
                    <input @keyup="search" type="text" v-model="qEmail" class="form-control" placeholder="Email">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les hotlines</h3>
        <div class="card-tools">
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
      <add-hotline @hotline-added="refreshAdded"></add-hotline>  
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Pseudo</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="hotline in hotlines.data" :key="hotline.id">
              <td>{{ hotline.nom }}</td>
              <td>{{ hotline.prenom }}</td>
              <td>{{ hotline.pseudo }}</td>
              <td>{{ hotline.email }}</td>
              <td>{{ hotline.tel }}</td>
              <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" @click="getHotline(hotline.id)">
                Modifier
                </button>
                <button @click="setId(hotline.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-danger">Supprimer</button>
                <delete-hotline v-bind:id="id" @hotline-deleted="refreshDeleted"></delete-hotline>
                <edit-hotline v-bind:hotlineToEdit="hotlineToEdit" @hotline-updated="refreshEdited"></edit-hotline>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :data="hotlines" @pagination-change-page="getResults"></pagination>
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
          hotlines: {},
          hotlineToEdit: '',
          q: new FormData(),
          qNom:'',
          qPrenom:'',
          qPseudo:'',
          qTel:'',
          qEmail:'',
          hidden:'true',
          id:''
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.get("http://localhost:8000/hotlines/liste")
        .then(response => this.hotlines = response.data)
        .catch(error => console.log(error))
      },
      methods:{
        getResults(page = 1) {
          axios.get('http://localhost:8000/hotlines/liste?page=' + page)
          .then(response => {
            this.hotlines = response.data;
          })
          .catch(error => console.log(error));
        },
        setId(id){
            this.id = id
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
        refreshDeleted(hotlines){
          this.hotlines = hotlines.data;
          var value = 'Hotline a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(hotlines){
          var value = 'Hotline a été modifié avec succées';
          this.hotlines = hotlines.data;
          this.toast(value);
        },
        refreshAdded(hotlines){
          var value = 'Hotline a été ajouté avec succées';
          this.hotlines = hotlines.data;
          this.toast(value);
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
        search(){
          this.q.append('role', 'HOTLINE');
          this.q.append('nom', this.qNom);
          this.q.append('prenom', this.qPrenom);
          this.q.append('pseudo', this.qPseudo);
          this.q.append('tel', this.qTel);
          this.q.append('email', this.qEmail);

          axios.post("http://localhost:8000/users/search", this.q)
          .then(response => this.hotlines = response.data)
          .catch(error => console.log(error))

        },
        refresh(hotlines){
          this.hotlines = hotlines.data; 
        },
        getHotline(id){
            axios.get('http://localhost:8000/hotlines/edit/' + id)
            .then(response => this.hotlineToEdit = response.data)
            .catch(error => console.log(error));
        },
      },
      mounted() {
        console.log('Component mounted.')
      }
    }
</script>