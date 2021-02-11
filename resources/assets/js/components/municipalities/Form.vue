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
          <h5 class="center-align">Agregrar nuevo municipio</h5>

          <form class="row" @submit.prevent="_submit">
            <div class="col s12 m6 l6 offset-m3 offset-l3">
              <!-- <label for="name" class="label-impegno">Nombre</label> -->
              <input
                type="text"
                name="name"
                placeholder="Nombre del municipio"
                id="name"
                v-model="form.name"
                maxlength="50"
                class="browser-default input-impegno"
              />
            </div>
            <div class="row">
              <div class="col s12">
                <br />
                <hr>
                <!-- <br /> -->
              </div>
            </div>
            <div class="row container-form">
              <h5 class="center-align">Agregar sectores</h5>
              <div class="col s6 offset-m3">
                <input
                placeholder="Nombre del sector ha agregar"
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

            <div class="row">
              <div class="col s9 offset-m3">
                <ul class="collection">
                  <li class="collection-item" v-for="(p, i) in form.parishes">
                    <div class="row" style="margin-bottom:0;">
                      <div class="col s8" style="font-size: 1.1rem; padding-top: .5rem;"><b>{{ p.name }}</b></div>
                      <div class="col s4">
                        <button class="btn btn-danger" @click.prevent="quit(x)" title="Quitar">X</button>
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
              <button href="#!" class="btn btn-success" :disabled="sending || !isValid">Guardar</button>
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
  props: ["estados", "estadoId"],
  computed: {
    isValid() {
      return this.form.name && this.form.estado_id;
    }
  },
  data() {
    return {
      form: {
        name: "",
        estado_id: "",
        free: 0,
        parishes: []
      },
      parish_name: "",
      sending: false,
      error: ''
    };
  },
  mounted() {
    console.log("estadoId", this.estadoId);
    console.log("estados", this.estados);
    this.form.estado_id = this.estadoId;
  },
  methods: {
    _back() {
      this.$emit("back", 0);
    },
    _submit() {
      this.sending = true;
      axios
        .post("admin/municipalities", this.form)
        .then(res => {
          this._showAlert("Registro exitoso", "success");
          this.$emit("stored", res.data);
          this._back();
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        })
        .finally(() => {
          this.sending = false;
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
        name: this.parish_name
      });
      this.parish_name = "";
    },
    quit(x) {
      this.form.parishes.splice(x, 1);
    }
  }
};
</script>

<style>
</style>