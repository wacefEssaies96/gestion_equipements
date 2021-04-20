<template>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header" :class="{'loading':loading}">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Chat</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
              <li class="breadcrumb-item active">Chat</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  <!-- DIRECT CHAT -->
  <div class="card direct-chat direct-chat-primary w-75 ml-4 float-left">
    <div class="card-header">
      <h3 class="card-title">Direct Chat</h3>
    </div>
    <!-- /.card-header -->

    <!-- Conversations are loaded here -->
    <div class="card-body">
      <h4>{{ contact ? contact.nom : 'Selectionner un utilisateur' }}</h4>
        <div class="direct-chat-messages">
          <!-- Message. Default to the left -->
          <div class="direct-chat-msg" v-for="message in messages" :key="message.id" :class="{'right ' : message.from == user.id}">
              <div class="direct-chat-infos clearfix">
                  <span class="direct-chat-name float-left" :class="{'float-right' : message.from == user.id}">
                    <template v-if="message.from == user.id">
                      {{user.nom}}
                    </template>
                    <template v-else>
                      {{contact.nom}}
                    </template>
                  </span>
              </div>
              <!-- /.direct-chat-infos -->
              <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
              <!-- /.direct-chat-img -->
              <div class="direct-chat-text">
                  {{message.text}}
              </div>
          <!-- /.direct-chat-text -->
          </div>
        <!-- /.direct-chat-msg -->
        </div>
    
      <!-- /.card-body -->
      <div class="card-footer">
        <form @submit.prevent="send" action="#" method="post">
          <div class="input-group">
          <input v-model="message" type="text" name="message" placeholder="écrire un message ..." class="form-control">
          <span class="input-group-append">
              <button @click="send" type="button" class="btn btn-primary">Envoyer</button>
          </span>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="card mr-4">
    <div class="card-header">
      <h3 class="card-title">Liste des utilisateurs</h3>
    </div>
    <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item" v-for="(contact, index) in contacts" :key="contact.id" @click="selectContact(index, contact)">
              <a href="#">
              <!-- <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar"> -->
              <!-- <div class="contacts-list-info"> -->
                  <!-- <span class="contacts-list-name"> -->
                  {{contact.nom}} {{contact.prenom}}
                  <!-- </span> -->
              <!-- </div> -->
              <!-- /.contacts-list-info -->
              </a>
          </li>
        <!-- End Contact Item -->
        </ul>
        <!-- /.contacts-list -->
    </div>
  </div>
  </div> 
</template>
<script>
export default {
    props:['user'],
     data(){
      return{
        selectedContact : null,
        messages: [],
        contacts: [],
        message:'',
        selected :0,
        contact:'',
        loading:true,
      }
    },

    created(){
      axios.get('/contacts')
      .then((response) => {
        this.contacts = response.data
        this.loading = false;  
      })
      .catch( error => console.log(error));
      this.messages = this.user.notifications;
      Echo.private('App.User.' + this.user.id)
      .notification((notification) => {
        if(this.selectedContact != null){
          this.messages.push(notification.notification.data.newMessage)
        }
      });
      this.messages = [];
    },

    methods:{

      saveNewMessage(message) {
        this.messages.push(message);
      },

      sendMessage(text) {
        if (!this.contact) {
          return;
        }
        axios.post('/conversation/send', {
            contact_id: this.contact.id,
            text: text
        }).then((response) => {
          this.saveNewMessage(response.data)
        })
      },

      send(){
        if(this.message == ''){
          return;
        }
        this.sendMessage(this.message);
        this.message = '';
      },

      selectContact(index, contact){
        this.contact = contact
        this.startConversation(contact);
      },
      
      startConversation(contact){
        this.messages = '';
        axios.get('/conversation/' + contact.id)
        .then(response => {
          this.messages = response.data;
          this.selectedContact = contact;
        })
        .catch(error => console.log(error))
      },
    }  
}
</script>