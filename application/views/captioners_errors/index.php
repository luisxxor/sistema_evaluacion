<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/css/inputmask.min.css" rel="stylesheet"/>

<style>
  [v-cloak], [v-cloak] > * {
    display: none;
  }

</style>

<div id="app">
  <v-app>
    <v-container>
      <h2 v-cloak class="text-xs-center">Listado de Errores</h2>
      <v-dialog v-model="feedback.show" v-cloak>
        <v-card>
          <v-card-title>
            <h4>{{ feedbackTitle }}</h4>
          </v-card-title>
          <v-card-text>
            <div id="failed" v-if="feedback.failed.length > 0">
              <p>Estas palabras ya existian y no fueron registradas:</p>
              <ul style="margin-bottom: 1em;">
                <li v-for="failed in feedback.failed">
                  {{ failed }}
                </li>
              </ul>
              <v-divider></v-divider>
            </div>           
            <div id="success" style="margin: 1em 0;" v-if="feedback.success.length > 0">
              <p>Se han registrado exitosamente las siguientes palabras:</p>
              <ul>
                <li v-for="success in feedback.success">
                  {{ success.word }}
                </li>
              </ul>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-layout>
        <v-flex>
          <v-dialog v-model="dialog" max-width="878px" persistent>
            <v-btn v-cloak slot="activator" color="222222" dark class="mb-2">Añadir error</v-btn>
            <v-card>
              <v-card-title>
                <span v-cloak class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-form ref="form" style="display: contents">
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-select :disabled="editmode" no-data-text="No hay digitadores registrados" :items="captioners" label="Digitador" :rules="[rules.required]" v-model="selected_captioner"></v-select>
                        <v-divider></v-divider>
                      </v-flex>

                    </v-layout>
                    <v-layout class="my-3">
                      <v-flex v-if="editmode">
                        <v-text-field
                          v-model="search"
                          append-icon="search"
                          label="Filtrar"
                          single-line
                          hide-details
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                    <v-layout>
                      <v-flex xs12>
                        <v-data-table
                          hide-actions
                          :headers="edit_headers"
                          :items="form"
                          class="elevation-1"
                          :loading="loading"
                          no-data-text="No ha insertado ningun error"
                          no-results-text="Ningun registro coincide con su búsqueda"
                          :search="search"
                        >
                          <template slot="items" slot-scope="props">
                            <td class="text-xs-left" v-if="editmode">
                              {{ props.item.created_date }}
                            </td>
                            <td class="text-xs-left">
                              <v-text-field :disabled="editmode && !userIsAdmin" :rules="[rules.required,cantBeEqual]" v-model="props.item.word"></v-text-field>
                            </td>
                            <td class="text-xs-left">
                              <v-textarea :disabled="editmode && !userIsAdmin" :rows="1" auto-grow v-model="props.item.description"></v-textarea>
                            </td>
                            <td v-if="userIsAdmin" style="height: 100%;">
                              <v-tooltip right v-cloak>
                                <v-icon
                                  slot="activator"
                                  @click="delete_item(props.item)"
                                  style="height: 100%; margin: auto 0;"
                                >
                                  close
                                </v-icon>
                                Eliminar
                              </v-tooltip>
                            </td>
                          </template>
                          <template slot="footer" v-if="!editmode">
                            <td :colspan="edit_headers.length" class="text-xs-center">
                              <v-btn outline color="#222222" @click="add_error">Añadir error</v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-flex>
                    </v-layout>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-cloak color="blue darken-1" flat @click="close">Cancelar</v-btn>
                <v-btn v-if="!(!userIsAdmin && editmode)" v-cloak color="blue darken-1" flat @click="save" :disabled="!canSubmit" >Guardar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-card>
            <v-data-table
              :headers="headers"
              :items="captioners_errors"
              class="elevation-1"
              :loading="loading"
              no-data-text="No hay registros"
              rows-per-page-text="Elementos por página"
            >
              <template slot="items" slot-scope="props">
                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.lastname }}</td>
                <td class="text-xs-left">{{ props.item.rut }}</td>
                  <td class="justify-start layout">
                    <v-tooltip right>
                      <v-icon
                        slot="activator"
                        small
                        @click="edit(props.item)"
                        style="height: 100%; margin: auto 0;"
                      >
                        visibility
                      </v-icon>
                      Ver
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
    captioners_errors: [],
    headers: [
      {text: 'Nombre', value: 'name', sortable: true },
      {text: 'Apellido', value: 'lastname', sortable: true},
      {text: 'Rut', value:"rut", sortable: true},
      {text: 'Acciones', value: "actions", sortable: false}
    ],
    loading: false,
    selected_captioner: null,
    form: [
    ],
    captioners: [],
    userIsAdmin: <?PHP echo json_encode(boolval($this->session->userdata('is_admin'))) ?>,
    editmode: false,
    editedIndex: -1,
    dialog: false,
    multiple_menu: [],
    search: '',
    rules: {
      required: value => !!value || 'Este campo es requerido.',
      maxLength: value => value.length < 10 || 'STAPH'
    },
    feedback: {
      show: false,
      success: [],
      failed: [],
      error: false
    }
  },
  methods: {
    add_error() {
      this.form.push({
        id: null,
        word: '',
        description: ''
      })
    },
    feedbackClose() {
      this.feedback = {
        show: false,
        success: [],
        failed: [],
        error: false
      }
    },
    cantBeEqual(value) {
      let count = 0;
      this.form.map(val => {
        if(val.word.toUpperCase() == value.toUpperCase())
        {
          count ++
        }
      })

      return count == 1 || 'No se pueden ingresar errores repetidos'
    },
    load() {
      this.loading = true;
      axios.get('read')
      .then(response => {
        this.loading = false;
        this.captioners_errors = response.data.captioners_errors
      })
      .catch(error => {
        this.loading = false;
      })
    },
    edit(item){
      axios.get('getErrorsFromCaptioner',{
        params: {
          captioner_id: item.id
        }
      })
      .then(({data: { errors }}) => {
        this.form = errors;
        this.selected_captioner =item.id;
        this.dialog = true;
        this.editmode = true;
      })
    },
    delete_item(item){
      let index = this.form.findIndex(i => i.word === item.word);
      Swal({
        title: '¿Estás seguro?',
        text: "¡Este elemento será eliminado!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '¡Si! ¡eliminar!',
        cancelButtonText: '¡No! ¡cancelar!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          if(this.editmode)
          {
            let data = new FormData();
            data.append('id',item.id);
            axios.post('delete',data)
            .then(response => {
              if(response)
              {
                Swal(
                  '¡Eliminado!',
                  'El error ha sido eliminado.',
                  'success'
                ).then(d => {
                  axios.get('getErrorsFromCaptioner',{
                    params: {
                      captioner_id: this.selected_captioner
                    }
                  }).then(({data: { errors }}) => {
                    if(errors.length > 0)
                    {
                      this.form = errors
                    }
                    else
                    {
                      Swal(
                        '¡Has eliminado todos los errores de este digitador!',
                        '',
                        'info'
                      ).then(e =>{
                        this.close();
                      })
                    }
                    this.load();
                  })
                })
              }
              else
              {
                Swal(
                  'Error',
                  'Ha ocurrido un error.',
                  'warning'
                ).then(response => {
                  console.log(response);
                })
              }
            })
          }
          else
          {
            this.form.splice(index,1);
          }
        } else if (
          result.dismiss === Swal.DismissReason.cancel
        ) {
          Swal(
            'Cancelado',
            'El error no fue eliminado.',
            'success'
          )
        }

      })
    },
    save(){
      if(this.$refs.form.validate())
      {
        let data = new FormData();
        data.append('error_form',JSON.stringify(this.form));
        data.append('captioner_id',this.selected_captioner);
          if(!this.editmode)
          {
            axios.post('create',data)
            .then(response => {
              this.dialog = false;
              this.feedback.failed = response.data.failed;
              this.feedback.success = Object.values(response.data.success);
              this.feedback.error = response.data.error;
              this.feedback.show = true;
              this.load();
            })
            .catch(error => {
              this.load();
            })
          }
          else
          {
            axios.post('update',data)
            .then(response => {
              swal('Excelente!','Errores actualizados correctamente','success')
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
        this.form = [];
        this.selected_captioner = null;
        this.$refs.form.reset();
        this.editmode = false;
      }, 300)
    },
  },
  created() {
    this.load();
    axios.get('../captioners/read')
    .then(response => {
      let captioners = response.data.captioners;

      let formattedCaptioners = [];

      captioners.forEach((item,index) => {
        formattedCaptioners.push({
          text: `${item.name} ${item.lastname.length > 0 ? item.lastname : ''} - ${item.rut}`,
          value: item.id
        })
      })

      this.captioners = formattedCaptioners;
    })
  },
  computed: {
    formTitle () {
      return !this.editmode ? 'Añadir errores' : 'Editar errores'
    },
    canSubmit() {
      return this.selected_captioner && (this.editmode || this.form.length > 0)
    },
    feedbackTitle() {
      return this.feedback.error ? 'Hubo un error' : 'Resultados de la operación'
    },
    feedbackShow() {
      return this.feedback.show;
    },
    edit_headers() {

      let editHeaders = [];

      if(this.editmode)
      {
        editHeaders.push({text: 'Fecha', value: 'created_date', sortable: true })
      }

      editHeaders.push({text: 'Palabra', value: 'word', sortable: this.editmode})
      editHeaders.push({text: 'Descripción', value:"description", sortable: this.editmode})

      if(this.userIsAdmin)
      {
        editHeaders.push({text: 'Acciones', value: "actions", sortable: false})
      }

      return editHeaders;
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    feedbackShow(val) {
      val || this.feedbackClose()
    }
  }
});


</script>