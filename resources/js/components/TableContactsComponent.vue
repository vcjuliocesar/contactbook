<template>
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(contact,index) in contacts" :key="contact.id">
        <td>{{contact.id}}</td>
        <td>{{contact.photo}}</td>
        <td>
          <input v-if="editMode" type="text" class="form-control" v-model="contact.name"/>
          <p v-else>{{contact.name}}</p>
        </td>
        <td>
          <input v-if="editMode" type="text" class="form-control" v-model="contact.email"/>
          <p v-else>{{contact.email}}</p>
        </td>
        <td>
          <button v-if="editMode" class="btn btn-success btn-icon-split" v-on:click="onClickUpdate(index,contact)">
            <span class="icon text-white-50">
              <i class="fas fa-edit"></i>
            </span>
            <span class="text">Save Changes</span>
          </button>
          <button v-else class="btn btn-warning btn-icon-split" v-on:click="onClickEdit()">
            <span class="icon text-white-50">
              <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
          </button>
          <button class="btn btn-danger btn-icon-split" v-on:click="onClickDelete(index)">
            <span class="icon text-white-50">
              <i class="fas fa-trash"></i>
            </span>
            <span class="text">Delete</span>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  /** contacts es una variable que es enviada por el pomponente padre */
  props: ["contacts"],
  data() {
    return {
      editMode: false
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    onClickDelete(index) {
      this.contacts.splice(index, 1);
    },
    onClickEdit() {
      this.editMode = true;
    },
    onClickUpdate(index,contact){
        this.editMode = false;
        this.contacts[index]=contact;
    }
  }
};
</script>
