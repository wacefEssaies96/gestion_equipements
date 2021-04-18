<template>
  <!-- DIRECT CHAT -->
  <div class="card direct-chat direct-chat-primary w-25 h-70" style="position: fixed; bottom: 0px; right: 10px;">
    <div class="card-header">
      <h3 class="card-title">Direct Chat</h3>

      <div class="card-tools">
        <!-- <span title="3 New Messages" class="badge badge-primary">3</span> -->
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" id="contacts" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
          <i class="fas fa-comments"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <!-- /.card-header -->

    <!-- Conversations are loaded here -->
    <div class="card-body">
      <h3>{{ contact ? contact.nom : 'Select a Contact' }}</h3>
      <!-- <template v-if="contact != ''"> -->

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
      <!-- </template> -->
      <!-- Contacts are loaded here -->
      <div class="direct-chat-contacts">
        <ul class="contacts-list">
          <li v-for="(contact, index) in contacts" :key="contact.id" @click="selectContact(index, contact)">
              <a href="#">
              <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">

              <div class="contacts-list-info">
                  <span class="contacts-list-name">
                  {{contact.nom}} {{contact.prenom}}
                  <!-- <small class="contacts-list-date float-right">2/28/2015</small> -->
                  </span>
                  <!-- <span class="contacts-list-msg">How have you been? I was...</span> -->
              </div>
              <!-- /.contacts-list-info -->
              </a>
          </li>
        <!-- End Contact Item -->
        </ul>
        <!-- /.contacts-list -->
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <form @submit.prevent="send" action="#" method="post">
          <div class="input-group">
          <input v-model="message" type="text" name="message" placeholder="Type Message ..." class="form-control">
          <span class="input-group-append">
              <button @click="send" type="button" class="btn btn-primary">Send</button>
          </span>
          </div>
        </form>
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
      }
    },

    mounted(){
      axios.get('/contacts')
      .then((response) => this.contacts = response.data)
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
        this.selected = index;
        this.contact = contact
        this.startConversation(contact);
      },
      
      startConversation(contact){
        this.messages = '';
        axios.get('/conversation/' + contact.id)
        .then(response => {
          this.messages = response.data;
          this.selectedContact = contact;
          document.getElementById('contacts').click();  
        })
        .catch(error => console.log(error))
      },
    }  
  }
</script>