<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Ofertas</h1>
      </div>

      <div class="row">
        <div class="col s12">
          <div class="col s12">
            <div class="row">
              <div class="col s12">
                <a :href="`${url}admin/offers`" class="btn btn-back">
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
                    <h5 class="center-align">Editar oferta</h5>
                  </div>
                </div>
                <div class="row">
                  <div class=" col s4">
                    <label for="">Fecha de inicio</label>
                    <input
                      type="text"
                      class="datepicker-start browser-default input-impegno"
                      name="start"
                      placeholder="Fecha de inicio"
                    />
                  </div>
                  <div class=" col s4">
                    <label for="">Fecha de finalización</label>
                    <input
                      type="text"
                      class="datepicker-end browser-default input-impegno"
                      name="end"
                      placeholder="Fecha de finalización"
                    />
                  </div>
                  <div class=" col s4">
                    <label for="">Porcentaje de descuento</label>
                    <input
                      name="percentage"
                      placeholder="Porcentaje de descuento"
                      class="browser-default input-impegno"
                      type="number"
                      v-model="form.percentage"
                    />
                  </div>
                </div>
                <div class="col s12">
                  <p
                    class="center-align red-text text-darken-1"
                    style="display: flex; justify-content: center;"
                  >
                    <i class="material-icons" style="margin-right: 5px;"
                      >info_outline</i
                    >
                    Para que el producto este disponible el mismo
                    <b>&nbsp;NO&nbsp;</b> debe estar en otra oferta
                    <b>&nbsp;activa</b>
                  </p>
                </div>
                <div class="row">
                  <div class="col s6">
                    <label class="center-align">
                      Seleccione los categoría/productos
                    </label>
                    <div class="">
                      <select
                        name="category_id"
                        id="category_id"
                        class="browser-default"
                        v-model="category_id"
                      >
                        <option value="">Seleccione</option>
                        <option
                          :value="item.id"
                          :key="index"
                          v-for="(item, index) in categories"
                          >{{ item.name }}</option
                        >
                      </select>
                    </div>
                    <ul class="collection" v-if="category_id">
                      <li class="collection-item">
                        <a href="#!" @click="addCategorie">
                          <i class="material-icons" v-if="isAll"
                            >radio_button_checked</i
                          >
                          <i class="material-icons" v-else
                            >radio_button_unchecked</i
                          >
                        </a>
                        <span class="title" style="margin-left: 10px;"
                          >Todos</span
                        >
                      </li>
                      <li
                        class="collection-item"
                        v-for="product in productsCategorie"
                        :key="product.id"
                      >
                        <a href="#!" @click="toggle(product.id)">
                          <i
                            class="material-icons"
                            v-if="products_id.includes(product.id)"
                            >radio_button_checked</i
                          >
                          <i class="material-icons" v-else
                            >radio_button_unchecked</i
                          >
                        </a>
                        <img
                          :src="product.image_url"
                          alt=""
                          width="30"
                          height="30"
                          style="margin-right: 10px; margin-left: 10px;"
                        />
                        <span class="title">{{ product.name }}</span>
                      </li>
                    </ul>
                  </div>
                  <div class="col s6">
                    <h6
                      class="mt-2 center-align"
                      v-show="products_id.length > 0"
                    >
                      Estos son los productos agregados a la oferta
                    </h6>
                    <ul class="collection" v-show="products_id.length > 0">
                      <li
                        class="collection-item"
                        v-for="product in productsAdded"
                        :key="product.id"
                      >
                        <i
                          title="Remover"
                          class="material-icons red-text text-darken-3"
                          @click.prevent="toggle(product.id)"
                          >clear</i
                        >
                        <img
                          :src="product.image_url"
                          alt=""
                          width="30"
                          height="30"
                          style="margin-right: 10px; margin-left: 10px;"
                        />
                        <span class="title">{{ product.name }}</span>
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="row">
                  <div class="col s12 m12 l12 margin-top center-align">
                    <button
                      href="#!"
                      class="btn btn-success"
                      :disabled="sending || isInvalid"
                      @click="submit"
                    >
                      Guardar
                    </button>
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
  name: "offer-edit",
  props: ["categories", "products", "offer"],
  data() {
    return {
      sending: false,
      category_id: null,
      products_id: [],
      form: {
        start: null,
        end: null,
        percentage: null,
      },
      endErr: false,
      startErr: false,
      url: "",
    };
  },
  computed: {
    isInvalid() {
      return (
        !this.form.start ||
        !this.form.end ||
        !this.form.percentage ||
        this.products_id.length === 0
      );
    },
    productsCategorie() {
      const category = this.categories.find(({ id }) => id == this.category_id);
      if (category) {
        return category.products;
      }
      return [];
    },
    isAll() {
      if (!this.category_id) {
        return false;
      }

      const notIsAll = this.productsCategorie.some(
        (p) => !this.products_id.includes(p.id)
      );

      return !notIsAll;
    },
    productsAdded() {
      return this.products.filter((p) => this.products_id.includes(p.id));
    },
  },
  mounted() {
    this.url = urlBase;
    this.form = { ...this.offer };
    console.log(new Date(this.form.start), new Date(this.form.end));
    this.products_id = [...this.offer.products.map((p) => p.id)];
    var startDate = document.querySelector(".datepicker-start");
    var endDate = document.querySelector(".datepicker-end");
    var instancesStart = M.Datepicker.init(startDate, {
      onSelect: this.startDate,
      minYear: minYear,
      maxYear: maxYear,
      onClose: this.verify,
      i18n: pickDateI18n,
      defaultDate: new Date(this.form.start),
      setDefaultDate: true,
      minDate: new Date(),
    });
    var instanceEnd = M.Datepicker.init(endDate, {
      onSelect: this.endDate,
      minYear: minYear,
      maxYear: maxYear,
      onClose: this.verify,
      i18n: pickDateI18n,
      defaultDate: new Date(this.form.end),
      setDefaultDate: true,
      minDate: new Date(),
    });
  },
  methods: {
    _back() {
      this.$emit("back", 0);
    },
    isAdded(productId) {
      return this.products_id.includes(productId);
    },
    toggle(productId) {
      if (this.products_id.includes(productId)) {
        const index = this.products_id.findIndex((id) => productId == id);
        this.products_id.splice(index, 1);
      } else {
        this.products_id.push(productId);
      }
    },
    addCategorie() {
      if (!this.isAll) {
        this.productsCategorie.map((p) => {
          if (!this.isAdded(p.id)) {
            this.products_id.push(p.id);
          }
        });
      } else {
        this.productsCategorie.map((p) => {
          if (this.isAdded(p.id)) {
            const index = this.products_id.findIndex((id) => p.id == id);
            this.products_id.splice(index, 1);
          }
        });
      }
    },
    submit() {
      if (this.form.percentage > 100) {
        this._showAlert(
          "El porcentaje de descuento no debe ser mayor a 100%",
          "warning"
        );
        return;
      }
      this.sending = true;
      const form = {
        ...this.form,
        products_id: this.products_id,
        _method: "patch",
      };
      axios
        .post(`admin/offers/${this.offer.id}`, form)
        .then((res) => {
          this._showAlert("Se ha actualizado correctamente la oferta");
          setTimeout(() => {
            window.location = window.urlBase + "/admin/offers";
          }, 1000);
        })
        .catch((err) => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        })
        .finally(() => {
          this.sending = false;
        });
    },
    startDate(date) {
      this.initErr = false;
      if (this.form.end && moment(date).isAfter(moment(this.form.end))) {
        swal(
          "",
          "La fecha de inicio no puede ser superior a la de finalización,  por favor vuelva a seleccionarla"
        );
        this.initErr = true;
        return;
      }
      this.form.start = date;
    },
    endDate(date) {
      this.endErr = false;
      if (this.form.start && moment(date).isBefore(moment(this.form.start))) {
        swal(
          "",
          "La fecha de finalización no puede ser antes la de inicio, por favor vuelva a seleccionarla"
        );
        this.endErr = true;
        this.init = "";
        return;
      }
      this.form.end = date;
    },
    verify() {
      if (this.startErr) {
        document.querySelector(".datepicker-start").value = "";
        this.form.start = "";
      }

      if (this.endErr) {
        document.querySelector(".datepicker-end").value = "";
        this.form.end = "";
      }
    },
    _showAlert(text, type) {
      swal({
        title: "",
        text: text,
        timer: 3000,
        showConfirmButton: false,
        type: type,
      });
    },
  },
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
