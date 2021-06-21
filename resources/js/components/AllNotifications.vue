<template>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Notifications</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Notifications</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  <!-- DIRECT CHAT -->
       <div class="card m-lg-4">
           <div class="card-header">
               <h3 class="card-title">Notifications</h3>
           </div>
           <div class="card-body">
               <ul class="list-group">
                   <li class="list-group-item" v-for="notification in data" :key="notification.id">
                        <template v-if="notification.appelleNonCloture">
                            <p>L'appelle {{notification.appelleNonCloture.id}} reste non cloturé.</p>
                        </template>
                        <template v-if="notification.confirmedHist">
                            <p>Le technicien à confirmé l'appelle {{notification.confirmedHist.id}}.</p>
                        </template>
                        <template v-if="notification.editedHist">
                            <p>Le technicien à rempli l'appelle {{notification.editedHist.id}}</p>
                        </template>
                        <template v-if="notification.createdHist">
                            <p>Vous avez une nouvelle interventions crée à {{notification.createdHist.id}}</p>
                        </template>
                    </li>
               </ul>
           </div>
           <div class="card-footer">

           </div>
       </div>
  </div> 
</template>
<script>
    export default {
        props:['user'],
        data(){
            return{
                notifications: [],
                data : [],
            }
        },
        created(){
            axios.post('/view-all-notifications',{
                id: this.user.id
            })
            .then(response => {
                this.notifications = response.data
                this.notifications.forEach(element => {
                    this.data.push(JSON.parse(element.data))
                });
            })
            .catch(error => console.log(error));
            Echo.private('App.User.' + this.user.id)
            .notification((notification) => {
                this.notifications.push(notification.notification)
                this.notifications.forEach(element => {
                    this.data.push(JSON.parse(element.data))
                });
                //console.log(this.notifications)
            });
            
        },
        methods:{}
        
    }
</script>