<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/css/inputmask.min.css" rel="stylesheet"/>

<style>
  [v-cloak], [v-cloak] > * {
    display: none;
  }

</style>

<div id="app">
  <v-app>
  <v-container>
    <h2 v-cloak class="text-xs-center">Listado de areas</h2>
    <v-layout>
      <v-flex>
        <v-dialog v-model="dialog" max-width="500px">
          <v-btn v-cloak slot="activator" color="222222" dark class="mb-2">Añadir area</v-btn>
          <v-card>
            <v-card-title>
              <span v-cloak class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-layout wrap>
                <v-form ref="form" style="display: contents">
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.name" :rules="[rules.required]" label="Nombre"></v-text-field>
                  </v-flex>
                </v-form>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn v-cloak color="blue darken-1" flat @click="close">Cancelar</v-btn>
              <v-btn v-cloak color="blue darken-1" flat @click="save" :disabled="!canSubmit" >Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-card>
          <v-data-table
            :headers="headers"
            :items="areas"
            class="elevation-1"
            :loading="loading"
            no-data-text="No hay registros"
            rows-per-page-text="Elementos por página"
          >
            <template slot="items" slot-scope="props">
              <td class="text-xs-left">{{ props.item.id }}</td>
              <td class="text-xs-left">{{ props.item.name }}</td>
              <td class="justify-start layout">
                <v-tooltip left>
                  <v-icon
                    slot="activator"
                    small
                    class="mr-2"
                    @click="editItem(props.item)"
                    style="height: 100%; margin: auto 0;"
                  >
                    edit
                  </v-icon>
                  Editar
                </v-tooltip>
                <v-tooltip right>
                  <v-icon
                    slot="activator"
                    small
                    @click="deleteItem(props.item)"
                    style="height: 100%; margin: auto 0;"
                  >
                    delete
                  </v-icon>
                  Eliminar
                </v-tooltip>
              </td>
            </template>
            <template slot="pageText" slot-scope="props">
              Mostrando elementos: {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
            </template>
          </v-data-table>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
  </v-app>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
new Vue({
  el: '#app',
  data: {
    areas: [],
    headers: [
      {
        text: 'ID',
        align: 'left',
        sortable: true,
        value: 'id'
      },
      {text: 'Nombres', value: 'name', sortable: true},
      {text: 'Acciones', value: "actions", sortable: false}
    ],
    loading: false,
    editedItem: {
      id: null,
      name: ''
    },
    defaultItem: {
      id: null,
      name: ''
    },
    editedIndex: -1,
    dialog: false,
    rules: {
      required: value => !!value || 'Este campo es requerido.'
    }
  },
  methods: {
    load() {
      this.loading = true;
      axios.get('read')
      .then(response => {
        this.loading = false;
        this.areas = response.data.areas
      })
      .catch(error => {
        this.loading = false;
        console.log(error)
      })
    },
    editItem(item){
      this.dialog = true;
      this.editedIndex = this.areas.indexOf(item);
      this.editedItem = Object.assign({},item);
    },
    deleteItem(item){
      Swal({
        title: '¿Estás seguro?',
        text: "El area sera eliminada permanentemente!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '¡Si! ¡eliminar!',
        cancelButtonText: '¡No! ¡cancelar!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          let data = new FormData();
          data.append('id',item.id);
          axios.post('delete',data)
          .then(response => {
            if(response)
            {
              Swal(
                '¡Eliminado!',
                'El area ha sido eliminada.',
                'success'
              ).then(response => {
                this.load();
              })
            }
            else
            {
              Swal(
                'Error',
                'Ha ocurrido un error.',
                'warning'
              ).then(response => {
                this.load();
              })
            }
          })
        } else if (
          result.dismiss === Swal.DismissReason.cancel
        ) {
          Swal(
            'Cancelado',
            'El area no fue eliminada.',
            'success'
          )
        }
      })
    },
    save(){
      let data = new FormData();
      data.append('area_form',JSON.stringify(this.editedItem));
      if(this.$refs.form.validate())
      {
        if(this.editedItem.id == null)
        {
          axios.post('create',data)
          .then(response => {
            swal('Excelente!','Area creada correctamente','success')
            .then(val => {
              this.load();
              this.dialog = false;
            })
          })
          .catch(error => {
            this.load();
          })
        }
        else
        {
          axios.post('update',data)
          .then(response => {
            swal('Excelente!','Area actualizada correctamente','success')
            .then(val => {
              this.load();
              this.dialog = false;
            })
          })
          .catch(error => {
            this.load();
          })
        }
      }
    },
    close(){
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
        this.$refs.form.reset()
      }, 300)
    }
  },
  created() {
    this.load();
  },
  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nueva area' : 'Editar area'
    },
    canSubmit() {
      if(this.dialog)
      {
        if(this.editedItem.name != undefined)
        {
          return this.editedItem.name.length > 0;
        }
        else
        {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    }
  }
});


</script>