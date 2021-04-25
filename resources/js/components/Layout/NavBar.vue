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
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" @click="markAsRead" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">{{unreadMessages.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item" v-for="message in unreadMessages" :key="message.id" @click="showChat(message.data.reciever)">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  {{message.data.reciever.nom}} {{message.data.reciever.prenom}}
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">{{message.data.newMessage.text}}</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <router-link to="/chat" class="dropdown-item dropdown-footer">Voir tous les messages</router-link>
        </div>
      </li>
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
    <template v-if="showChatb == true">
      <Chat :user="user" v-bind:userContact="userContact"></Chat>
    </template>
  </nav>
  <!-- /.navbar -->
</template>
<script>
import Chat from '../Chat/Chat';

export default {

  components:{
    Chat
  },

  props:['user'],

  data(){
    return{
      allNotifications:'',
      allMessages:'',
      um:'',
      showChatb:false,
      userContact : '',
    }
  },

  methods:{
    showChat(contact){
      this.userContact = contact;
      this.showChatb = true;
    },
    markAsRead(){
      axios.get('/mark-as-read/'+this.user.id)
      // .then(response => this.unreadMessages = [])
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
            return notification.read_at == null && (!notification.data.newMessage)
        })
      },
      set: function(newVal){
        return newVal;
      }
    },
    unreadMessages:{
      get: function(){
        return this.allMessages.filter(notification=>{
            return notification.read_at == null && notification.data.newMessage
        })
      },
      set: function(newVal){
        unreadMessages = newVal;
      }
    },
  },
  created(){
    this.allNotifications = this.user.notifications;
    this.allMessages = this.user.notifications;
    Echo.private('App.User.' + this.user.id)
    .notification((notification) => {
      if(notification.notification.data.newMessage){
        this.allMessages.push(notification.notification);
        this.toast('Vous avez un nouveau message !');
      }
      else{
        this.allNotifications.push(notification.notification)
        this.toast('Vous avez une nouvelle notification !');
      }
    });
  }
}
</script>