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
        <form
          action
          v-on:submit.prevent="newContact()"
          v-on:keydown="clear($event.target.name)"
          enctype="multipart/form-data"
        >
          <div class="modal-body">
            <div class="form-group">
              <label for="photo">Photo</label>
              <input
                type="file"
                name="image"
                ref="image"
                class="form-control-file"
                :class="{errors:hasError('image')}"
                v-on:change="onFileChange"
                id="exampleFormControlFile1"
              />
              <span v-if="hasError('image')" v-text="getError('image')" class="error-message"></span>
            </div>
            <div class="form-group">
              <label for="Name">Name</label>
              <input
                type="text"
                :class="{errors:hasError('name')}"
                class="form-control"
                id="ContactName"
                placeholder="Name"
                v-model="name"
              />
              <span v-if="hasError('name')" v-text="getError('name')" class="error-message"></span>
            </div>
            <div class="form-group">
              <label for="Name">Email address</label>
              <input
                type="email"
                class="form-control"
                :class="{errors:hasError('email')}"
                id="ContactEmail"
                aria-describedby="emailHelp"
                placeholder="Enter email"
                v-model="email"
              />
              <span v-if="hasError('email')" v-text="getError('email')" class="error-message"></span>
            </div>
          </div>
          <div class="modal-footer">
            <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="any()"
            >Save</button>
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
      email: "",
      errors: {}
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
          $("#exampleModal").modal("toggle");
          this.$refs.image.value = '';
          this.image = "";
          this.name = "";
          this.email = "";
        })
        .catch((error) => {
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
