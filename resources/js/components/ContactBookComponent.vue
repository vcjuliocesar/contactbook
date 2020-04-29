<template>
  <div class="container">
    <h1>Contacts</h1>

    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        <button
          class="btn btn-success btn-icon-split"
          data-toggle="modal"
          data-target="#exampleModal"
        >
          <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
          </span>
          <span class="text">Add new</span>
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <!--Envio de datos de un componente padre a un componente hijo-->
          <v-contacts-table :contacts="contacts"></v-contacts-table>
        </div>
      </div>
    </div>

    <add-contact @new="addContact"></add-contact>
  </div>
</template>

<script>
export default {
  data() {
    return {
      contacts: []
    };
  },
  mounted() {
    axios.get('/contacts').then((response)=>{
        this.contacts = response.data;
    }).catch((error)=>{
        console.log(error);
    });
  },
  methods: {
    addContact(contact) {
      this.contacts.push(contact);
    }
  }
};
</script>
