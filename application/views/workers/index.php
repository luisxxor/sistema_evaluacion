<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/css/inputmask.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<style>
  [v-cloak], [v-cloak] > * {
    display: none;
  }

</style>

<div id="app">
  <v-app>
  <v-container>
    <h2 v-cloak class="text-xs-center">Listado de trabajadores</h2>
    <v-layout>
      <v-flex>
        <v-dialog v-model="dialog" max-width="500px">
          <v-btn v-cloak slot="activator" color="222222" dark class="mb-2">Añadir trabajador</v-btn>
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
                  <v-flex 12>
                    <v-menu
                      ref="admission_date"
                      v-model="menu"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="editedItem.admission_date"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="editedItem.admission_date"
                        label="Fecha de admisión"
                        :rules="[rules.required]" 
                        readonly
                      ></v-text-field>
                      <v-date-picker :max="moment().format()" locale="es" v-model="editedItem.admission_date" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn v-cloak flat color="primary" @click="menu = false">Cancelar</v-btn>
                        <v-btn v-cloak flat color="primary" @click="$refs.admission_date.save(editedItem.admission_date)">Seleccionar</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12>
                    <v-autocomplete :rules="[rules.required]" v-model="editedItem.position_id" :items="positions" item-text="name" item-value="id" label="Cargo"></v-autocomplete>
                  </v-flex>
                  <v-flex xs12>
                    <v-autocomplete :rules="[rules.required]" v-model="editedItem.area_id" :items="areas" item-text="name" item-value="id" label="Area"></v-autocomplete>
                  </v-flex>
                </v-form>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn v-cloak color="blue darken-1" flat @click="close">Cancelar</v-btn>
              <v-btn v-cloak color="blue darken-1" flat @click="save" :disabled="canSubmit" >Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-card>
          <v-data-table
            :headers="headers"
            :items="workers"
            class="elevation-1"
            :loading="loading"
            no-data-text="No hay registros"
            rows-per-page-text="Elementos por página"
          >
            <template slot="items" slot-scope="props">
              <td class="text-xs-left">{{ props.item.id }}</td>
              <td class="text-xs-left">{{ props.item.name }}</td>
              <td class="text-xs-left">{{ props.item.admission_date }}</td>
              <td class="text-xs-left">{{ props.item.position }}</td>
              <td class="text-xs-left">{{ props.item.area }}</td>
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
    workers: [],
    areas: [],
    positions: [],
    headers: [
      {
        text: 'ID',
        align: 'left',
        sortable: true,
        value: 'id'
      },
      {text: 'Nombres', value: 'name', sortable: true},
      {text: 'Fecha de ingreso', value: 'admission_date', sortable: true },
      {text: 'Cargo', value:"position", sortable: true},
      {text: 'Area', value:"area", sortable: true},     
      {text: 'Acciones', value: "actions", sortable: false}
    ],
    loading: false,
    editedItem: {
      id: null,
      name: '',
      admission_date: '',
      position: null,
      area: null
    },
    defaultItem: {
      id: null,
      name: '',
      admission_date: '',
      position: null,
      area: null
    },
    editedIndex: -1,
    dialog: false,
    menu: false,
    rules: {
      required: value => !!value || 'Este campo es requerido.',
      maxLength: value => value.length < 10 || 'STAPH'
    }
  },
  methods: {
    moment() {
      return moment();
    },
    load() {
      this.loading = true;
      axios.get('read')
      .then(response => {
        this.loading = false;
        this.workers = response.data.workers
      })
      .catch(error => {
        this.loading = false;
        console.log(error)
      })

      axios.get('<?PHP echo base_url("areas/read"); ?>')
      .then(response => {
        this.areas = response.data.areas;
      })
      axios.get('<?PHP echo base_url("positions/read"); ?>')
      .then(response => {
        this.positions = response.data.positions;
      })
    },
    editItem(item){
      this.dialog = true;
      this.editedIndex = this.workers.indexOf(item);
      this.editedItem = Object.assign({},item);
    },
    deleteItem(item){
      Swal({
        title: '¿Estás seguro?',
        text: "¡El digitador será eliminado para siempre! ¡y con él todos los errores relacionados!",
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
                'El digitador ha sido eliminado.',
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
            'El digitador no fue eliminado.',
            'success'
          )
        }
      })
    },
    save(){
      let data = new FormData();
      data.append('worker_form',JSON.stringify(this.editedItem));
      if(this.$refs.form.validate())
      {
        if(this.editedItem.id == null)
        {
          axios.post('create',data)
          .then(response => {
            swal('Excelente!','Trabajador creado correctamente','success')
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
            swal('Excelente!','Captioner actualizado correctamente','success')
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
      return this.editedIndex === -1 ? 'Nuevo trabajador' : 'Editar trabajador'
    },
    canSubmit() {
      return false;
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    }
  }
});


</script>