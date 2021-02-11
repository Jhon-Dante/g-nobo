<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Descuentos</h1>
      </div>
      <div class="col s12">
        <div class="row">
          <div class="col s12">
            <a href="#!" class="btn btn-back" @click="_back">
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
                <h5 class="center-align">Editar descuento</h5>
                <p class="col s12 center-align">
                  Campos requeridos
                  <strong class="red-text text-darken-1">(*)</strong>
                </p>
              </div>
            </div>
            <div class="row">
              <div class="col s6">
                <label for=""
                  >Nombre del descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  type="text"
                  class=" browser-default input-impegno"
                  name="start"
                  placeholder="Nombre"
                  v-model="form.name"
                />
              </div>
              <div class="col s6" v-if="form.type !== 'quantity_purchase'">
                <label for="">Limite de uso por cliente</label>
                <input
                  name="percentage"
                  placeholder="Limite"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.limit"
                />
              </div>
            </div>
            <div class="row">
              <div class=" col s4">
                <label for=""
                  >Fecha de inicio
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  type="text"
                  class="datepicker-start browser-default input-impegno"
                  name="start"
                  placeholder="Fecha de inicio"
                />
              </div>
              <div class=" col s4">
                <label for=""
                  >Fecha de finalización
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  type="text"
                  class="datepicker-end browser-default input-impegno"
                  name="end"
                  placeholder="Fecha de finalización"
                />
              </div>
              <div class=" col s4">
                <label for=""
                  >Porcentaje de descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  name="percentage"
                  placeholder="Porcentaje de descuento"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.percentage"
                />
              </div>
            </div>
            <div class="row" style="margin-top: 25px; margin-bottom: 25px;">
              <hr />
              <div class="col s4">
                <p>
                  <label>
                    <input
                      name="type"
                      value="quantity_product"
                      type="radio"
                      v-model="form.type"
                    />
                    <span>Descuento por cantidad de productos</span>
                  </label>
                </p>
              </div>
              <div class="col s4">
                <p>
                  <label>
                    <input
                      name="type"
                      value="minimum_purchase"
                      type="radio"
                      v-model="form.type"
                    />
                    <span>Descuento por monto de compra</span>
                  </label>
                </p>
              </div>
              <div class="col s4">
                <p>
                  <label>
                    <input
                      name="type"
                      value="quantity_purchase"
                      type="radio"
                      v-model="form.type"
                    />
                    <span>Descuento nro. de compras</span>
                  </label>
                </p>
              </div>
            </div>
            <div class="row">
              <div class="col s6" v-if="form.type == 'minimum_purchase'">
                <label for=""
                  >Monto mínimo para aplicar el descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  name="percentage"
                  placeholder="Monto mínimo"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.minimum_purchase"
                />
              </div>
              <div class="col s6" v-if="form.type == 'quantity_product'">
                <label for=""
                  >Cantidad productos para el aplicar descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  name="percentage"
                  placeholder="Cantidad productos"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.quantity_product"
                />
              </div>
              <div class="col s6" v-if="form.type == 'quantity_purchase'">
                <label for=""
                  >Cantidad de compras necesarias para aplicar el descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  name="percentage"
                  placeholder="Cantidad de compras"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.quantity_purchase"
                />
              </div>
            </div>
            <div class="row" v-if="form.type == 'quantity_product'">
              <div class="col s6">
                <label>
                  Seleccione los categoría/productos
                  <strong class="red-text text-darken-1">(*)</strong>
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
                    <span class="title" style="margin-left: 10px;">Todos</span>
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
                <h6 class="mt-2 center-align" v-show="products_id.length > 0">
                  Estos son los productos agregados al descuento
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
  </section>
</template>

<script>
export default {
  name: "discount-edit",
  props: ["categories", "products", "discount"],
  data() {
    return {
      sending: false,
      category_id: "",
      products_id: [],
      form: {
        name: "",
        start: null,
        end: null,
        percentage: null,
        type: "quantity_product",
        limit: null,
        minimum_purchase: null,
        quantity_product: null,
        quantity_purchase: null,
      },
      endErr: false,
      startErr: false,
      url: "",
    };
  },
  computed: {
    isInvalid() {
      return (
        !this.form.name ||
        !this.form.start ||
        !this.form.end ||
        !this.form.percentage
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
  methods: {
    _back() {
      window.location = `${this.url}admin/discounts`;
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
        products_id:
          this.form.type === "quantity_product" ? this.products_id : [],
        _method: "patch",
      };

      axios
        .post(`admin/discounts/${this.discount.id}`, form)
        .then((res) => {
          this._showAlert("Registro exitoso", "success");
          setTimeout(this._back, 1000);
        })
        .catch((err) => {
          if (err.response.status === 422) {
            this._showAlert(err.response.data.message, "error");
            return;
          }
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
  mounted() {
    this.url = urlBase;
    this.form = { ...this.discount };
    this.products_id = [...this.discount.products.map((p) => p.id)];
    var startDate = document.querySelector(".datepicker-start");
    var endDate = document.querySelector(".datepicker-end");
    var instancesStart = M.Datepicker.init(startDate, {
      onSelect: this.startDate,
      onClose: this.verify,
      maxYear: maxYear,
      minYear: minYear,
      defaultDate: new Date(this.form.start),
      setDefaultDate: true,
      i18n: pickDateI18n,
      minDate: new Date(),
    });
    var instanceEnd = M.Datepicker.init(endDate, {
      onSelect: this.endDate,
      onClose: this.verify,
      maxYear: maxYear,
      minYear: minYear,
      i18n: pickDateI18n,
      defaultDate: new Date(this.form.end),
      setDefaultDate: true,
      minDate: new Date(),
    });
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
