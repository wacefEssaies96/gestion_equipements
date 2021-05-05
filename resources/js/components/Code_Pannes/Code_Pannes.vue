<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Code Pannes</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/">Acceuil</router-link></li>
              <li class="breadcrumb-item active">Code Pannes</li>
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
                    <label>Code</label>
                    <input @keyup="search" type="text" v-model="qCode" class="form-control" placeholder="Code panne">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label>Désignation</label>
                    <input @keyup="search" type="text" v-model="qDesi" class="form-control" placeholder="Désignation">
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
            </div>
          </div>
        </template>
      <div class="card-header">
        <h3 class="card-title">Liste de tous les codes pannes</h3>
        <div class="card-tools">
          <button @click="exportData()" class="btn btn-secondary">
            <i class="fas fa-upload" title="Exporter les données"> Exporter</i>
          </button>
          <!-- Button trigger modal -->
          <button class="btn btn-secondary" data-toggle="modal" data-target="#addFromExcel">
            <i class="fas fa-download" title="Ajouter via fichier excel"> Importer</i>
          </button>
          <button class="btn btn-outline-info" data-toggle="modal" data-target="#exampleModalCenter">
            <i class="fas fa-plus" title=" Ajouter un nouveau code panne"></i>
          
          </button>
          <button class="btn btn-outline-info" @click="showSearch"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
    <AddCodePanne @codePanne-added="refreshAdded"></AddCodePanne>
    <AddFromExcel v-bind:type="'codePanne'" @codePanne-added="refreshAdded"></AddFromExcel>   
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>Code Panne</th>
              <th>Designation</th>
              <th>Zone</th>  
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="code_panne in code_pannes.data" :key="code_panne.id">
              <td>{{ code_panne.code }}</td>
              <td>{{ code_panne.designation }}</td>
              <td>{{ code_panne.zone }}</td>
              <td>
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#editModal" @click="getCode_Panne(code_panne.id)">
                  <i class="fas fa-edit" title="Modifier"/>
                </button>              
                <button @click="setId(code_panne.id)" data-toggle="modal" data-target="#modal-danger" type="button" class="btn btn-outline-danger">
                   <i class="fas fa-trash-alt" title="Supprimer"/>
                  </button>
                <DeleteCodePanne v-bind:id="id" @codePanne-deleted="refreshDeleted"></DeleteCodePanne>
                <EditCodePanne v-bind:codePanneToEdit="codePanneToEdit" @codePanne-updated="refreshEdited"></EditCodePanne>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination class="m-auto" :limit="5"  :data="code_pannes" @pagination-change-page="getResults"></pagination>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</template>
<script>
  import AddCodePanne from './AddCode_Panne';
  import EditCodePanne from './EditCode_Panne';
  import DeleteCodePanne from './DeleteCode_Panne';
  import AddFromExcel from '../AddFromExcel'
    export default {
        components:{
          AddCodePanne,
          EditCodePanne,
          DeleteCodePanne,
          AddFromExcel
      },
      props:['user'],
      data(){
        return{
          code_pannes: {},
          codePanneToEdit: '',
          qCode: '',
          qZone: '',
          qDesi: '',
          q: new FormData(),
          hidden: 'true',
          id:'',
          loading:true,
        }
      },
      created(){
        if(this.user.role != 'ADMIN'){
          this.$router.push('/');
        }
        axios.post("/code_pannes/liste")
        .then(response => {
          this.code_pannes = response.data;
          this.loading = false;
          })
        .catch(error => console.log(error))
      },
      methods:{
        exportData(){
          axios.get('code-pannes/export', {responseType: 'blob'})
          .then((response) => {
              const url = URL.createObjectURL(new Blob([response.data], {
                  type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
              }))
              const link = document.createElement('a')
              link.href = url
              link.setAttribute('download', 'CodePanneListe')
              document.body.appendChild(link)
              link.click()
          });
        },
        getResults(page = 1) {
          axios.post('/code_pannes/liste?page=' + page)
          .then(response => {
            this.code_pannes = response.data;
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
        refreshDeleted(code_pannes){
          this.code_pannes = code_pannes.data;
          var value = 'Code panne a été supprimé avec succées';
          this.toast(value);
        },
        refreshEdited(code_pannes){
          var value = 'Code panne a été modifié avec succées';
          this.code_pannes = code_pannes.data;
          this.toast(value);
        },
        refreshAdded(code_pannes){
          var value = 'Code panne a été ajouté avec succées';
          this.code_pannes = code_pannes.data;
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
          this.q.append('code', this.qCode);
          this.q.append('zone', this.qZone);
          this.q.append('designation', this.qDesi);

          axios.post("/code_pannes/liste", this.q)
          .then(response => this.code_pannes = response.data)
          .catch(error => console.log(error))

        },
        refresh(code_pannes){
          this.code_pannes = code_pannes.data; 
        },
        getCode_Panne(id){
            axios.get('/code_pannes/edit/' + id)
            .then(response => this.codePanneToEdit = response.data)
            .catch(error => console.log(error));
        },
        
      },
    }
</script>