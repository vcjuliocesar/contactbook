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
        <td>
          <input
            v-if="editMode == contact.id"
            type="file"
            name="image"
            class="form-control-file"
            v-on:change="onFileChange"
            id="exampleFormControlFile1"
          />

          <img
            v-else
            :src="'http://127.0.0.1:8000/storage/images/'+contact.image"
            class="rounded mx-auto d-block"
            width="96"
            height="65"
          />
        </td>
        <td>
          <input
            v-if="editMode == contact.id"
            type="text"
            class="form-control"
            v-model="contact.name"
          />
          <p v-else>{{contact.name}}</p>
        </td>
        <td>
          <input
            v-if="editMode == contact.id"
            type="text"
            class="form-control"
            v-model="contact.email"
          />
          <p v-else>{{contact.email}}</p>
        </td>
        <td>
          <button
            v-if="editMode == contact.id"
            class="btn btn-success btn-icon-split"
            v-on:click="onClickUpdate(index,contact)"
          >
            <span class="icon text-white-50">
              <i class="fas fa-edit"></i>
            </span>
            <span class="text">Save Changes</span>
          </button>
          <button v-else class="btn btn-warning btn-icon-split" v-on:click="onClickEdit(contact)">
            <span class="icon text-white-50">
              <i class="fas fa-edit"></i>
            </span>
            <span class="text">Edit</span>
          </button>
          <button class="btn btn-danger btn-icon-split" v-on:click="onClickDelete(index,contact)">
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
      image: "",
      editMode: 0,
      flagImage: false
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    onFileChange(e) {
      this.image = e.target.files[0];
      this.flagImage = true;
    },
    onClickDelete(index, contact) {
      axios
        .delete(`/contacts/${contact.id}`)
        .then(response => {
          this.contacts.splice(index, 1);
        })
        .catch(error => {
          console.log(error);
        });
    },
    onClickEdit(data) {
      this.editMode = data.id;
    },
    onClickUpdate(index, contact) {
      const config = {
        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')
          .content,
        headers: { "content-type": "multipart/form-data" }
      };
      var newImage = "";
      if (this.image != undefined) {
        newImage = this.image;
      }
      let formData = new FormData();
      formData.append('image',newImage);
      formData.append('name',contact.name);
      formData.append('email',contact.email);
      formData.append('flagImage',this.flagImage);
      /*const params = {
        image: newImage,
        name: contact.name,
        email: contact.email,
        flagImage: this.flagImage,
        /*"X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')
          .content,
        headers: { "content-type": "multipart/form-data" }
      };
      console.log(params);*/
      axios
        .post(`/contacts/${contact.id}`, formData,config)
        .then(response => {
          //console.log(response.data);
          this.editMode = 0;
          this.flagImage = false;
          this.contacts[index] = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
