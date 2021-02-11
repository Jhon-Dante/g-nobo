<template>
  <div class="col s12">
    <div class="row">
      <div class="col s12">
        <a href="#!" class="btn btn-back" @click="_back()">
          <div class="btn-back__container">
            <div class="btn-back__ico"></div>
            <label for>Volver</label>
          </div>
        </a>
      </div>
    </div>

    <card-main>
      <card-content>
        <div class="container">
          <form class="row" @submit.prevent="_submit">
            <div class="col s12 m6 l6 offset-l3">
              <label for="name" class="label-impegno">Nombre</label>
              <input
                type="text"
                name="name"
                id="name"
                v-model="form.name"
                maxlength="50"
                class="browser-default input-impegno"
              />
            </div>

            <!-- <div class="col s12 m6 l6">
              <label for="name_english" class="label-impegno">Estado</label>
              <select
                name="estado_id"
                id="estado_id"
                class="browser-default"
                v-model="form.estado_id"
              >
                <option value>Seleccione</option>
                <option
                  :value="item.id"
                  :key="index"
                  v-for="(item, index) in estados"
                >{{ item.nombre }}</option>
              </select>
            </div> -->

            <div class="col s12 offset-l2">
              <h5 class="center-align" style="margin-top:1rem;">Agregar sector</h5>
              <div class="col s6">
                <input
                placeholder="Nombre"
                  type="text"
                  name="name"
                  id="name"
                  v-model="parish_name"
                  maxlength="50"
                  class="browser-default input-impegno"
                  @focus="error = ''"
                />
                <div style="font-size: .85rem; color:red;" v-show="error">{{ error }}</div>
              </div>
              <div class="col s1 container-btn-add"><br>
                <button class="btn-add" @click.prevent="_addParish()">
                  <img :src="'img/icons/new-msg.png' | asset" alt class="img-responsive" />
                </button>
              </div>
            </div>

            <div class="col s12 offset-l2">
              <div class="col s9">
                <ul class="collection">
                  <li class="collection-item" v-for="(p, i) in form.parishes" :key="i" v-if="!p.deleted">
                    <div class="row" style="margin-bottom:0;">
                      <div class="col s8" style="font-size: 1.1rem; padding-top: .5rem;"><b>{{ p.name }}</b></div>
                      <div class="col s4">
                        <button class="btn btn-danger" @click.prevent="quit(i)" title="Quitar">X</button>
                      </div>
                    </div>
                  </li>
                  <li class="collection-item" v-if="form.parishes.length == 0">
                    No ha agregado sectores
                  </li>
                </ul>
              </div>
            </div>
            <div class="col s12 m12 l12 margin-top center-align">
              <button href="#!" class="btn btn-success" :disabled="isSending || !isValid">Guardar</button>
            </div>
          </form>
        </div>
      </card-content>
    </card-main>
  </div>
</template>

<script>
export default {
  name: "municipality-form",
  props: ["estados", "municipality"],
  computed: {
    isValid() {
      return this.form.name && this.form.estado_id;
    }
  },
  data() {
    return {
      form: {
        id: "",
        name: "",
        estado_id: "",
        parishes: []
      },
      isSending: false,
      parish_name: "",
      error: ''
    };
  },
  mounted() {
    this.form = { 
      ...this.municipality,
      parishes: this.municipality.parishes.map(m => ({...m, deleted: false}))
     };
  },
  methods: {
    _back() {
      this.$emit("back", 0);
    },
    _submit() {
      this.form._method = "patch";
      this.isSending = true;
      axios
        .post(`admin/municipalities/${this.form.id}`, this.form)
        .then(res => {
          this._showAlert("Se actualizó el municipio exitosamente", "success");
          this.$emit("updated", res.data);
          this._back();
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        })
        .finally(() => {
          this.isSending = false;
        });
    },
    _showAlert(text, type) {
      swal({
        title: "",
        text: text,
        timer: 3000,
        showConfirmButton: false,
        type: type
      });
    },
     _addParish() {
      if (!this.parish_name || this.parish_name.length < 3) {
        this.error = 'El nombre debe tener minimo 3 caracteres'
        return;
      }
      this.form.parishes.push({
        id: 0,
        name: this.parish_name
      });
      this.parish_name = "";
    },
    quit(x) {
      if(this.form.parishes[x].id == 0) {
        this.form.parishes.splice(x, 1);
      } else {
        this.form.parishes[x].deleted = true;
      }
    }
  }
};
</script>

<style>
</style>