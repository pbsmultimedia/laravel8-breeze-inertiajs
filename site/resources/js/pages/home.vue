<template>
  <admin-layout>
    <v-banner class="mb-4">
      <div class="d-flex flex-wrap justify-space-between">
        <h5 class="text-h5 font-weight-bold">
          Maintenances
        </h5>
        <v-breadcrumbs
          :items="breadcrumbs"
          class="pa-0"
        />
      </div>
    </v-banner>
    <v-card>
      <v-card-title>
        Filters
      </v-card-title>
      <div>
        <v-container class="mb-2">
          <v-row>
            <v-col
              md="6"
              cols="12"
            >
              <v-autocomplete
                v-model="search.client"
                :items="clients"
                label="Client"
                item-text="name"
                item-value="id"
              />
            </v-col>
            <v-col
              md="6"
              cols="12"
            >
              <v-autocomplete
                v-model="search.brand"
                :items="brands"
                label="Brand"
                item-text="name"
                item-value="id"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-spacer />
              <v-btn color="primary" @click="updateData">
                <v-icon>mdi-magnify</v-icon>
                Search
              </v-btn>
              <v-btn @click="reset">
                <v-icon>mdi-reset</v-icon>
                Reset
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </div>
    </v-card>
    <div class="d-flex flex-wrap align-center mb-2">
      <v-spacer />
      <v-btn color="primary" @click="create">
        <v-icon dark left>mdi-plus</v-icon>
        New
      </v-btn>
    </div>
    <v-data-table
      :items="maintenances.data"
      :headers="headers"
      :server-items-length="maintenances.total"
      :loading="isLoadingTable"
      :options.sync="options"
      class="elevation-1"
    >
      <template #[`item.index`]="{ index }">
        {{ (options.page - 1) * options.itemsPerPage + index + 1 }}
      </template>
      <template #[`item.action`]="{ item }">
        <v-btn @click="editItem(item)">
          <v-icon small>mdi-pencil</v-icon>
        </v-btn>
        <v-btn @click="deleteItem(item)">
          <v-icon small>mdi-delete</v-icon>
        </v-btn>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog" max-width="500px" scrollable>
      <v-card>
        <v-toolbar dense dark color="primary" class="text-h6">
          New
        </v-toolbar>
        <v-card-text class="pt-4">
          <v-autocomplete
            v-model="form.client"
            :items="clients"
            label="Client"
            item-text="name"
            item-value="id"
            :error-messages="form.errors.client"
          />
        </v-card-text>
        <v-card-actions>
          <v-btn :disabled="form.processing" text color="error" @click="dialog = false">Cancel</v-btn>
          <v-spacer />
          <v-btn :loading="form.processing" color="primary" @click="submit">
            Save
          </v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500">
      <v-card>
        <v-toolbar dense dark color="primary" class="text-h6">
          Delete Client
        </v-toolbar>
        <v-card-text class="text-h6">
          Are you sure you want to delete this client?
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn :disabled="form.processing" text color="error" @click="dialogDelete = false">
            Cancel
          </v-btn>
          <v-btn :loading="form.processing" text color="primary" @click="destroy">
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </admin-layout>
</template>

<script>
import AdminLayout from "../layouts/AdminLayout.vue";
export default {
  components: { AdminLayout },
  props: [
    'brands',
    'clients',
  ],
  data() {
    return {
      maintenances: [],
      headers: [
        { text: "ID", value: "id" },
        { text: "Name", value: "client_name" },
        { text: "Email", value: "email" },
        { text: "Car", value: "brand_name" },
        { text: "Plate", value: "registration_plate" },
        { text: "Cost", value: "cost" },
        { text: "Date", value: "date" },
        { text: "Actions", value: "action", sortable: false },
      ],
      breadcrumbs: [
        {
          text: "Home",
          disabled: true,
          href: "/",
        },
      ],
      isLoadingTable: false,
      options: {},
      params: {},
      search: {
        brand: null,
        client: null,
      },
      dialog: false,
      form: this.$inertia.form({
        client: null,
      }),
      dialogDelete: false,
    }
  },
  methods: {
    create() {
      this.dialog = true;
      this.form.reset();
      this.form.clearErrors();
      this.updateData();
    },
    async updateData() {
      this.isLoadingTable = true
      // TODO: optimize this
      axios.get('/maintenance', {
        params: {...this.params, ...this.search}
      })
        .then(response => {
          this.isLoadingTable = false;
          this.maintenances = response.data;
        });
    },
    reset() {
      this.search = {};
      this.params = {};
      this.updateData();
    },
    editItem(item) {
      this.form.clearErrors();
      this.isUpdate = true;
      this.itemId = item.id;
      this.dialog = true;
    },
    deleteItem(item) {
      this.itemId = item.id;
      this.dialogDelete = true;
    },
    destroy() {
      // ...
    },
    submit() {
      this.$inertia.post('/client', {
        errorBag: 'createPost',
        wantsJson: true,
        onSuccess: (data) => {
          console.log('ok!', data);
        }
      });

    },
  },
  watch: {
    options: function (val) {
      this.params.page = val.page;
      this.params.page_size = val.itemsPerPage;
      if (val.sortBy.length) {
        this.params.sort_by = val.sortBy[0];
        this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
      } else {
        this.params.sort_by = null;
        this.params.order_by = null;
      }
      this.updateData();
    },
  },
  computed: {
    clientsComputed() {
      return this.clients
    }
  }
};
</script>
