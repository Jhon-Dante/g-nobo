<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Estados</h1>
      </div>

      <div class="row">
        <div class="col s12">
          <section class="table__content">
            <div class="row">
              <table-byte :set-table="data" :filters="['name']">
                <table-row slot="table-head" slot-scope="{ item }">
                  <table-head>Nombre</table-head>
                  <table-head>Fecha de registro</table-head>
                  <table-head>Acciones</table-head>
                </table-row>
                <table-row slot="table-row" slot-scope="{ item }">
                  <table-cell>{{ item.nombre }}</table-cell>
                  <table-cell>{{ item.created_at | date }}</table-cell>
                  <table-cell>
                    <a :href="`${url}admin/estados/${item.id}`" class="btn-action" title="Editar">
                      <img :src="'img/icons/ico-editar.png' | asset" alt class="img-responsive" />
                    </a>

                    <a
                      href="#!"
                      class="btn-action"
                      @click="_confirm(item)"
                      :title="`${item.status ? 'Desactivar': 'Activar'}`"
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
                  </table-cell>
                </table-row>

                <table-row slot="empty-rows">
                  <table-cell colspan="4">No se encontraron registros.</table-cell>
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
                        <b>{{ `${item.status ? 'desactivar ' : 'activar'}` }}</b>este estado?
                      </h5>
                      <p class="center-align">Esto hara que {{ `${item.status ? 'no' : ''}` }} sea visible para las entregas</p>
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
  name: "minicipality-index",
  props: ["estados"],
  data() {
    return {
      data: [],
      options: 0,
      option: '',
      url: '',
      item: null,
      modal: null
    };
  },
  mounted() {
    this.url = urlBase
    this.data = [...this.estados];
    this.modal = M.Modal.init(document.querySelector(".modal"));
  },
  methods: {
    _confirm(item) {
      this.item = item
      this.option = this.toggleStatus
      this.modal.open();
    },
    toggleStatus() {
      this.modal.close();
      axios.post(`admin/estados/${this.item.id}/status`)
        .then(res => {
          let index = this.data.findIndex(d => d.id == this.item.id)
          this.data[index].status = res.data.status
          this._showAlert("Se actualizó el registro exitosamente", "success");
        }).catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
          console.log(err)
        })
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
