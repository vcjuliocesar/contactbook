<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action v-on:submit.prevent="newContact()" enctype="multipart/form-data">
          <div class="modal-body">
            <!--<form>-->
            <div class="form-group">
              <label for="photo">Photo</label>
              <input
                type="file"
                name="image"
                class="form-control-file"
                v-on:change="onFileChange"
                id="exampleFormControlFile1"
              />
            </div>
            <div class="form-group">
              <label for="Name">Name</label>
              <input
                type="text"
                class="form-control"
                id="ContactName"
                placeholder="Name"
                v-model="name"
              />
            </div>
            <div class="form-group">
              <label for="Name">Email address</label>
              <input
                type="email"
                class="form-control"
                id="ContactEmail"
                aria-describedby="emailHelp"
                placeholder="Enter email"
                v-model="email"
              />
            </div>
            <!--</form>-->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" v-on:click="hideModal()">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      image: "",
      name: "",
      email: ""
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    onFileChange(e) {
      this.image = e.target.files[0];
    },
    newContact() {
      const config = {
        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')
          .content,
        headers: {
          "content-type": "multipart/form-data"
        }
      };

      let formData = new FormData();
      formData.append("image", this.image);
      formData.append("name", this.name);
      formData.append("email", this.email);

      axios
        .post("/contacts", formData, config)
        .then(response => {
          const contact = response.data;
          this.$emit("new", contact);
        })
        .catch(error => {
          console.log(error);
        });

      this.image = "";
      this.name = "";
      this.email = "";
    },
    hideModal() {
      $("#exampleModal").modal("toggle");
    }
  }
};
</script>
