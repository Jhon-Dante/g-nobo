<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Municipios del estado {{ estado.nombre }}</h1>
      </div>

      <div class="row">
        <div class="col s12">
          <section class="table__content" v-if="options == 0">
            <div class="row">
              <div class="col s12 container-btn-add">
                <button class="btn-add" @click="options = 1">
                  <img :src="'img/icons/new-msg.png' | asset" alt class="img-responsive" />
                </button>
                <div class="btn-add-text">Agregar nuevo</div>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m12">
                <span for>
                  <b>Todos</b> los municipios gratuitos:
                </span>
                <a href="#!" class="btn-action" @click="_confirm(0, '_freeAll')" :title="`${estadoFree ? 'Desactivar todos' : 'Activar todos'}`">
                  <img
                    :src="'img/icons/ico-toggle-on.svg' | asset"
                    alt
                    class="img-responsive"
                    style="width: 36px; margin: 0;"
                    v-if="estadoFree == '1'"
                  />
                  <img
                    :src="'img/icons/ico-toggle-off.svg' | asset"
                    alt
                    class="img-responsive"
                    style="width: 36px; margin: 0;"
                    v-if="estadoFree == '0'"
                  />
                </a>
              </div>
            </div>
            <div class="row">
              <table-byte :set-table="data" :filters="['name']">
                <table-row slot="table-head" slot-scope="{ item }">
                  <table-head>Nombre</table-head>
                  <table-head>Estado</table-head>
                  <table-head>Fecha de registro</table-head>
                  <table-head>Acciones</table-head>
                  <table-head>Envío gratuito</table-head>
                </table-row>
                <table-row slot="table-row" slot-scope="{ item }">
                  <table-cell>{{ item.name }}</table-cell>
                  <table-cell>{{ item.estado.nombre }}</table-cell>
                  <table-cell>{{ item.created_at | date }}</table-cell>
                  <table-cell>
                    <a href="#!" class="btn-action" @click="_edit(item)" title="Ver">
                      <img :src="'img/icons/ico-editar.png' | asset" alt class="img-responsive" />
                    </a>

                    <a
                      href="#!"
                      class="btn-action"
                      @click="_confirm(item.id, '_deleted')"
                      title="Eliminar"
                    >
                      <img :src="'img/icons/ico-eliminar.png' | asset" alt class="img-responsive" />
                    </a>
                  </table-cell>
                  <table-cell>
                    <a
                      href="#!"
                      class="btn-action"
                      @click="_confirm(item.id, '_free',item)"
                      :title="`${item.free ? 'Desactivar': 'Activar'}`"
                    >
                      <img
                        :src="'img/icons/ico-toggle-on.svg' | asset"
                        alt
                        class="img-responsive"
                        style="width: 36px; margin: 0;"
                        v-if="item.free"
                      />
                      <img
                        :src="'img/icons/ico-toggle-off.svg' | asset"
                        alt
                        class="img-responsive"
                        style="width: 36px; margin: 0;"
                        v-if="!item.free"
                      />
                    </a>
                  </table-cell>
                </table-row>

                <table-row slot="empty-rows">
                  <table-cell colspan="4">No se encontraron registros.</table-cell>
                </table-row>
              </table-byte>
            </div>
          </section>

          <municipality-form
            v-if="options == 1"
            @back="_back"
            :estadoId="estadoId"
            :estados="estados"
            @stored="_stored"
          ></municipality-form>
          <municipality-edit
            v-if="options == 2"
            @back="_back"
            @updated="_updated"
            :estados="estados"
            :municipality="municipality"
          ></municipality-edit>

          <byte-modal v-on:pressok="option" :confirm="true">
            <template>
              <div class="container-confirmation">
                <div class="confimation__icon">
                  <i class="material-icons">error_outline</i>
                </div>
                <div class="confirmation__text">
                  <h5 v-if="optionSelected == '_deleted'">
                    ¿Realmente deseas
                    <b>Eliminar</b> este Producto?
                  </h5>
                  <h5 v-if="optionSelected == '_freeAll'" class="text-center">
                    ¿Realmente deseas
                    <b>{{ `${estadoFree ? 'desactivar' : 'activar'}` }} envío gratuito</b> para todos los municipios?
                  </h5>
                  <h5 v-if="optionSelected == '_free'" class="text-center">
                    ¿Realmente deseas
                    <b>{{ `${selected.free ? 'desactivar' : 'activar'}` }} envío gratuito</b> para este municipio?
                  </h5>
                </div>
              </div>
            </template>
          </byte-modal>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "minicipality-index",
  props: ["municipalities", "estados", "estadoId", "estado"],
  data() {
    return {
      id: null,
      data: [],
      options: 0,
      modal: null,
      option: "",
      optionSelected: "",
      estadoFree: false,
      selected: {}
    };
  },
  mounted() {
    this.data = [...this.municipalities];
    this.estadoFree = this.estado.free;
    console.log("data", this.data);
    this.modal = M.Modal.init(document.querySelector(".modal"));
  },
  methods: {
    _edit(item) {
      this.municipality = item;
      this.options = 2;
    },
    _confirm(id, option, selected = {}) {
      this.id = id;
      this.optionSelected = option;
      this.option = this[option];
      this.selected = selected;
      this.modal.open();
    },
    _back(option) {
      this.options = option;
    },
    _stored(item) {
      this.data = [item, ...this.data];
    },
    _updated(item) {
      if (item.estado_id == this.estadoId) {
        this.data = [
          ...this.data.map(m => {
            return m.id == item.id ? item : m;
          })
        ];
      } else {
        this.data = [...this.data.filter(m => m.id != item.id)];
      }
    },
    _deleted() {
      let index = this.data.findIndex(e => e.id == this.id);

      this.modal.close();

      axios
        .delete(`admin/municipalities/${this.id}`)
        .then(res => {
          this.data.splice(index, 1);
          this._showAlert("Se eliminó el registro exitosamente", "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },
    _freeAll() {
      this.modal.close();
      axios
        .post(`admin/estados/${this.estadoId}/free`)
        .then(res => {
          this.data = this.data.map(d => {
            d.free = res.data.free;
            return d;
          });
          this.estadoFree = res.data.free;
          this._showAlert("Se guardo el cambio exitosamente", "success");
        })
        .catch(err => console.log("err", err));
    },
    _free() {
      const index = this.data.findIndex(d => d.id == this.id);
      this.modal.close();
      axios
        .post(`admin/municipalities/${this.id}/free`)
        .then(res => {
          console.log("municipality", res.data);
          console.log(this.data[index]);
          this.data[index].free = res.data.free;
          if (!res.data.free) {
            this.estadoFree = false;
          } 
          this._showAlert("Se guardo el cambio exitosamente", "success");
        })
        .catch(err => console.log("err", err));
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

<style>
</style>
