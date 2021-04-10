<template>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"
          ><i class="fas fa-bars"></i
        ></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <router-link to="/" class="nav-link">Dashboard</router-link>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a @click="markAsRead" class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{unreadNotifications.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{unreadNotifications.length}} Notifications</span>
          <div v-for="notification in unreadNotifications" :key="notification.id">
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <template v-if="notification.data.createdHist">
                <i class="fas fa-file mr-2"></i> Vous avez une nouvelle <br>
                interventions créer à {{notification.data.createdHist.heure_demande}} .
                <span class="float-right text-muted text-sm"></span>
              </template>
              <template v-if="notification.data.confirmedHist">
                <i class="fas fa-file mr-2"></i> Un technicien à confirmé <br>
                l'appelle {{notification.data.confirmedHist.id}} .
                <span class="float-right text-muted text-sm"></span>
              </template>
              <template v-if="notification.data.editedHist">
                <i class="fas fa-file mr-2"></i> Un technicien à rempli <br>
                l'appelle {{notification.data.editedHist.id}} .
                <span class="float-right text-muted text-sm"></span>
              </template>
              <template v-if="notification.data.appelleNonCloture">
                <i class="fas fa-file mr-2"></i> L'appelle {{notification.data.appelleNonCloture.id}} reste non cloturé.
                <span class="float-right text-muted text-sm"></span>
              </template>   
            </a>
          </div>
          <div class="dropdown-divider"></div>
          <!-- <a href="#" class="dropdown-item dropdown-footer"
            >See All Notifications</a
          > -->
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
</template>
<script>
export default {
    props:['user'],

    data(){
        return{
            allNotifications:'',
        }
    },
    methods:{
      markAsRead(){
        axios.get('/mark-as-read/'+this.user.id)
        // .then(response => this.allNotifications = [])
        .catch(error => console.log(error));
      },
      toast(value) {
        this.$swal.fire({
        icon: "warning",
        title: value,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        });
      },   
    },

    computed:{
      unreadNotifications:{
        get: function(){
          return this.allNotifications.filter(notification=>{
              return notification.read_at == null
          })
        },
        set: function(newVal){
          console.log(newVal);
          return newVal;
        }
      }
    },
    created(){
        this.allNotifications = this.user.notifications
        console.log(this.allNotifications);
        Echo.private('App.User.' + this.user.id)
        .notification((notification) => {
          this.allNotifications.push(notification.notification)
          this.toast('Vous avez une nouvelle notification !');
        });
    }
}
</script>