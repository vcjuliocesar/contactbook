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
      <tr v-for="(contact,index) in contacts" :key="contact.id"   v-on:keydown="clear($event.target.name)">
        <td>{{contact.id}}</td>
        <td>
          <input
            v-if="editMode == contact.id"
            type="file"
            name="image"
            class="form-control-file"
            :class="{errors:hasError('image')}"
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
            :class="{errors:hasError('name')}"
            v-model="contact.name"
          />
          <p v-else>{{contact.name}}</p>
        </td>
        <td>
          <input
            v-if="editMode == contact.id"
            type="text"
            class="form-control"
            :class="{errors:hasError('email')}"
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
      flagImage: false,
      errors: {}
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
      formData.append("image", newImage);
      formData.append("name", contact.name);
      formData.append("email", contact.email);
      formData.append("flagImage", this.flagImage);

      axios
        .post(`/contacts/${contact.id}`, formData, config)
        .then(response => {
          //console.log(response.data);
          this.editMode = 0;
          this.flagImage = false;
          this.contacts[index] = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(this.errors);
        });
    },
    any() {
      return Object.keys(this.errors).length > 0;
    },
    hasError(field) {
      return this.errors.hasOwnProperty(field);
    },

    getError(field) {
      if (this.errors[field]) {
        return this.errors[field][0];
      }
    },

    clear(field) {
      if (field) {
        delete this.errors[field];
        return;
      }
      this.errors = {};
    }
  }
};
</script>
