<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Impuestos</h1>
      </div>

      <div class="row">
        <div class="col s12">
          <div class="col s12">
            <div class="row">
              <div class="col s12">
                <a :href="`${url}admin/taxes`" class="btn btn-back">
                  <div class="btn-back__container">
                    <div class="btn-back__ico"></div>
                    <label for>Volver</label>
                  </div>
                </a>
              </div>
            </div>

            <card-main>
              <card-content>
                <div class="row">
                  <div class="col s12">
                    <h5 class="center-align">Editar impuesto</h5>
                  </div>
                </div>
                <div class="row">
                  <div class="col s4">
                    <label for>Nombre</label>
                    <input
                      type="text"
                      class="browser-default input-impegno"
                      name="start"
                      placeholder="Nombre"
                      v-model="form.name"
                    />
                  </div>
                  <div class="col s4">
                    <label for>Descripción</label>
                    <input
                      type="text"
                      class="browser-default input-impegno"
                      name="end"
                      placeholder="Descripción"
                      v-model="form.description"
                    />
                  </div>
                  <div class="col s4">
                    <label for>Porcentaje de impuesto</label>
                    <input
                      name="percentage"
                      placeholder="Porcentaje de descuento"
                      class="browser-default input-impegno"
                      type="number"
                      v-model="form.percentage"
                    />
                  </div>
                </div>

                <div class="row">
                  <div class="col s12 m12 l12 margin-top center-align">
                    <button
                      href="#!"
                      class="btn btn-success"
                      :disabled="sending || isInvalid"
                      @click="submit"
                    >Guardar</button>
                  </div>
                </div>
              </card-content>
            </card-main>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "taxe-edit",
  props: ["taxe"],
  data() {
    return {
      sending: false,
      form: {
        name: null,
        description: null,
        percentage: null
      },
      url: ""
    };
  },
  computed: {
    isInvalid() {
      return !this.form.name || !this.form.description || !this.form.percentage;
    }
  },
  mounted() {
    this.url = urlBase;
    this.form = { ...this.taxe };
    console.log("form", this.taxe);
  },
  methods: {
    _back() {
      this.$emit("back", 0);
    },
    submit() {
      if (this.form.percentage > 100) {
        this._showAlert(
          "El porcentaje de impuesto no debe ser mayor a 100%",
          "warning"
        );
        return;
      }
      this.sending = true;
      const form = {
        ...this.form,
        _method: "patch"
      };
      axios
        .post(`admin/taxes/${this.taxe.id}`, form)
        .then(res => {
          this._showAlert(
            "Se ha actualizado correctamente el registro",
            "success"
          );
          setTimeout(() => {
            window.location = window.urlBase + "/admin/taxes";
          }, 1000);
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
    }
  }
};
</script>

<style lang="scss">
.collection {
  max-height: 400px;
  overflow: hidden;
  overflow-y: auto;
  .collection-item {
    display: flex;
    align-items: center;
  }
  .material-icons {
    cursor: pointer;
  }
}
.mt-2 {
  margin-top: 59px;
}
</style>
