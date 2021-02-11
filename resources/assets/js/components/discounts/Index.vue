<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Descuentos</h1>
      </div>

      <div class="row">
        <div class="col s12">
          <section class="table__content" v-if="options == 0">
            <div class="row">
              <div class="col s12 container-btn-add">
                <button class="btn-add" @click="toCreate">
                  <img :src="'img/icons/new-msg.png' | asset" alt class="img-responsive" />
                </button>
                <div class="btn-add-text">Agregar nuevo</div>
              </div>
            </div>
            <div class="row">
              <table-byte :set-table="data">
                <table-row slot="table-head" slot-scope="{ item }">
                  <table-head>Nombre</table-head>
                  <table-head>Inicio</table-head>
                  <table-head>Finalización</table-head>
                  <table-head>Procentaje</table-head>
                  <table-head>Tipo</table-head>
                  <table-head class="center-align">Condición</table-head>
                  <table-head style="min-width: 110px;" class="center-align">Acciones</table-head>
                </table-row>
                <table-row slot="table-row" slot-scope="{ item }">
                  <table-cell>{{ item.name }}</table-cell>
                  <table-cell>{{ item.start | date }}</table-cell>
                  <table-cell>{{ item.end | date }}</table-cell>
                  <table-cell>{{ item.percentage }}%</table-cell>
                  <table-cell>{{ item.type_text }}</table-cell>
                  <table-cell class="center-align">{{ item.type_unit }} {{ item[item.type] }}</table-cell>
                  <table-cell>
                    <a
                      :href="`${url}admin/discounts/${item.id}/edit`"
                      class="btn-action"
                      title="Editar"
                    >
                      <img :src="'img/icons/ico-editar.png' | asset" alt class="img-responsive" />
                    </a>

                    <a
                      href="#!"
                      class="btn-action"
                      @click="_confirm(item, toggleStatus)"
                      :title="`${item.status ? 'Desactivar' : 'Activar'}`"
                    >
                      <img
                        :src="'img/icons/ico-toggle-on.svg' | asset"
                        alt
                        class="img-responsive"
                        style="width: 36px; margin: 0;"
                        v-if="item.status"
                      />
                      <img
                        :src="'img/icons/ico-toggle-off.svg' | asset"
                        alt
                        class="img-responsive"
                        style="width: 36px; margin: 0;"
                        v-if="!item.status"
                      />
                    </a>

                    <!-- <a
                      href="#!"
                      class="btn-action"
                      @click="_confirm(item, _deleted, 'delete')"
                      title="Eliminar"
                    >
                      <img :src="'img/icons/ico-eliminar.png' | asset" alt class="img-responsive" />
                    </a> -->
                  </table-cell>
                </table-row>

                <table-row slot="empty-rows">
                  <table-cell colspan="6">No se encontraron registros.</table-cell>
                </table-row>
              </table-byte>

              <byte-modal v-on:pressok="option" :confirm="true">
                <template>
                  <div class="container-confirmation">
                    <div class="confimation__icon">
                      <i class="material-icons">error_outline</i>
                    </div>
                    <div class="confirmation__text" v-if="item">
                      <h5 class="text-center">
                        ¿Realmente deseas
                        <b v-if="action == 'activar'">
                          {{
                          `${item.status ? "desactivar " : "activar"}`
                          }}
                        </b>
                        <b v-else>eliminar</b>
                        este descuento?
                      </h5>
                    </div>
                  </div>
                </template>
              </byte-modal>
            </div>
          </section>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "offer-index",
  props: ["discounts", "categories", "products"],
  data() {
    return {
      data: [],
      options: 0,
      option: "",
      url: "",
      item: null,
      modal: null,
      action: "activar"
    };
  },
  mounted() {
    this.url = urlBase;
    this.data = [...this.discounts];
    this.modal = M.Modal.init(document.querySelector(".modal"));
  },
  methods: {
    toCreate() {
      window.location = `${this.url}admin/discounts/create`;
    },
    _confirm(item, method, action = "activar") {
      this.item = item;
      this.option = method;
      this.action = action;
      this.modal.open();
    },
    _back(option) {
      this.options = option;
    },
    _stored(item) {
      this.data = [item, ...this.data];
    },
    _deleted() {
      let index = this.data.findIndex(e => e.id == this.item.id);

      this.modal.close();

      axios
        .delete(`admin/discounts/${this.item.id}`)
        .then(res => {
          this.data.splice(index, 1);
          this._showAlert("Se eliminó el registro exitosamente", "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },
    toggleStatus() {
      showLoading();
      this.modal.close();
      axios
        .post(`admin/discounts/${this.item.id}/status`)
        .then(res => {
          let index = this.data.findIndex(d => d.id == this.item.id);
          this.data[index].status = res.data.status;
          this._showAlert("Se actualizó el registro exitosamente", "success");
        })
        .catch(err => {
          if (err.response.status === 422) {
            this._showAlert(err.response.data.message, "error", 6000);
            return;
          }
          this._showAlert("Disculpa, ha ocurrido un error", "error");
          console.log(err.response);
        })
        .finally(() => {
          quiLoading();
        });
    },
    _showAlert(text, type, timer = 3000) {
      swal({
        title: "",
        text: text,
        timer: timer,
        showConfirmButton: false,
        type: type
      });
    }
  }
};
</script>

<style></style>
