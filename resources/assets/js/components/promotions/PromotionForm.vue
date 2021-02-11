<template>
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1>Promociones</h1>
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
                <h5 class="center-align">Crear nueva Promocion</h5>
                <p class="col s12 center-align">
                  Campos requeridos
                  <strong class="red-text text-darken-1">(*)</strong>
                </p>
              </div>
            </div>
            <div class="row">
              <div class="col s4">
                <label for=""
                  >Titulo
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  type="text"
                  class=" browser-default input-impegno"
                  name="start"
                  placeholder="Titulo"
                  v-model="form.title"
                />
              </div>
              <div class="col s4">
                <label for="">Limite de uso por cliente
                  <strong class="red-text text-darken-1">(*)</strong>
                </label>
                <input
                  name="limit"
                  placeholder="Usos"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.limit"
                />
              </div>
              <div class="col s4 center-align div-image-margin-top">
                  <label for="">Imagen
                    <strong class="red-text text-darken-1">(*)</strong>
                  </label>
                  <input-file :btn="false" :image="true" v-on:file="_setFile($event)"></input-file>
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
                  name="start_date"
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
                  name="end_date"
                  placeholder="Fecha de finalización"
                />
              </div>
              <div class=" col s4">
                <label for=""
                  >Porcentaje de descuento
                  <strong class="red-text text-darken-1">(*)</strong></label
                >
                <input
                  name="discount_percentage"
                  placeholder="Porcentaje de descuento"
                  class="browser-default input-impegno"
                  type="number"
                  v-model="form.discount_percentage"
                />
              </div>
            </div>
            <div class="row">
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
                    @change="selectedProduct = null"
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
                  <li
                    class="collection-item"
                    v-for="product in productsCategorie"
                    :key="product.id"
                  >
                    <a href="#!" @click="selectProduct(product)">
                      <i
                        class="material-icons"
                        v-if="selectedProduct && selectedProduct.id == product.id"
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
                <h6 class="mt-2 center-align" v-show="productsAdded.length > 0">
                  Estas son las presentaciones del producto
                </h6>
                <ul class="collection" v-show="productsAdded.length > 0">
                  <li
                    class="collection-item"
                    v-for="product_amount in productsAdded"
                    :key="product_amount.product_id"
                  >
                    <div class="col s4">
                      <span>
                        {{ product_amount.unit ? product_amount.presentation : '' }} 
                        {{ product_amount.unit ? getUnit(product_amount.unit) + ' X ' : '' }}
                        {{ product_amount.price }}$
                        - Cantidad:
                      </span>
                    </div>
                    <div class="col s7">
                      <input
                          name="amount"
                          placeholder="Cantidad"
                          class="browser-default input-impegno"
                          type="number"
                          v-model="product_amount.total"
                      />
                    </div>
                    <div class="col s1">
                     <i
                        title="Agregar"
                        class="material-icons green-text text-darken-3"
                        @click.prevent="toggle(product_amount)"
                        >add
                      </i>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="row">
              <div class="col s6">
                <h6 class="mt-2 center-align" v-show="products.length > 0">
                  Estos son los productos agregados a la promocion
                </h6>
                <ul class="collection" v-show="products.length > 0">
                  <li
                    class="collection-item"
                    v-for="product in products"
                    :key="product.product_id"
                  >
                    <div class="col" :class="product.existence ? 's11' : 's6'">
                      <span> 
                        {{ product.name }} 
                        {{ product.unit ? product.presentation : '' }} 
                        {{ product.unit ? getUnit(product.unit) : '' }} 
                        - Cantidad: {{ product.total }}
                      </span>
                    </div>
                    <div v-if="product.total > product.existence" class="col s5">
                      <span class="red-text text-darken-3"> 
                        Cantidad no disponible
                      </span>
                    </div>
                    <div v-if="!product.existence" class="col s5">
                      <span class="red-text text-darken-3"> 
                        Sin existencia
                      </span>
                    </div>
                    <div class="col s1">
                      <i
                        title="Remover"
                        class="material-icons red-text text-darken-3"
                        @click.prevent="toggle(product)"
                        >clear</i
                      >
                    </div>
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

<style lang="scss">
  .div-image-margin-top {
    margin-top: -8rem;
  }
</style>

<script>
import { unities } from "../../constants";
export default {
  name: "promotions-create",
  props: ["categories"],
  data() {
    return {
        sending: false,
        category_id: "",
        products: [],
        form: {
            title: "",
            image: "",
            limit: null,
            start_date: null,
            end_date: null,
            discount_percentage: null
        },
        image: "",
        endErr: false,
        startErr: false,
        url: "",
        selectedProduct: null
    };
  },
  computed: {
    isInvalid() {
      return (
        !this.form.title ||
        !this.form.start_date ||
        !this.form.end_date ||
        !this.form.discount_percentage ||
        !this.form.limit ||
        !this.form.image
      );
    },
    productsCategorie() {
      const category = this.categories.find(({ id }) => id == this.category_id);
      if (category) {
        return category.products;
      }
      return [];
    },
    productsAdded() {
        let productAmounts = [];
        if(this.selectedProduct) {
            if(this.selectedProduct.colors.length > 0) {
                const amounts = this.selectedProduct.colors[0].amounts.map((amount) => {
                    return {
                        ...amount,
                        total: 0,
                        name: this.selectedProduct.name,
                        master_id: this.selectedProduct.id,
                        existence: amount.amount
                    }
                })
                productAmounts = amounts
            }
        }
        return productAmounts;
    },
  },
  methods: {
    _back() {
      window.location = `${this.url}admin/promotions`;
    },
    getUnit(unit) {
      return unities.find(u => u.id == unit).name;
    },
    toggle(product_amount) {
        const index = this.products.findIndex((product) => product_amount.product_id == product.product_id);
        if(index >= 0){
            const currentProduct = this.products[index];
            currentProduct.total = 0;
            this.products.splice(index, 1);
            if(currentProduct.master_id == this.selectedProduct.id) {
              this.productsAdded.push(currentProduct);
            }
        } else {
            const indexAdded = this.productsAdded.findIndex((product) => product_amount.product_id == product.product_id);
            if(product_amount.total <= 0) {
                this._showAlert(
                "La cantidad de la presentacion debe ser superior a 0",
                "warning"
                );
                return;
            }
            // if(!product_amount.unit) {
            //     this._showAlert(
            //     "La presentacion debe poseer una unidad asignada",
            //     "warning"
            //     );
            //     return;
            // }
            this.products.push(product_amount);
            this.productsAdded.splice(indexAdded, 1);
        }
    },
    selectProduct(product) {
        this.selectedProduct = product;
    },
    _setFile(file) {
        if(!file.file.type.match("image.*")) {
            return swal('', 'Solo se aceptan imagenes', 'error')
        }
        this.image = file.file
        this.form.image = file.file      
    },
    submit() {
      if (this.form.discount_percentage > 100) {
        this._showAlert(
          "El porcentaje de descuento no debe ser mayor a 100%",
          "warning"
        );
        return;
      }
      this.sending = true;

      axios
        .post("admin/promotions", this._convertToFormData())
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
      if (this.form.end_date && moment(date).isAfter(moment(this.form.end_date))) {
        swal(
          "",
          "La fecha de inicio no puede ser superior a la de finalización,  por favor vuelva a seleccionarla"
        );
        this.initErr = true;
        return;
      }
      this.form.start_date = date;
    },
    endDate(date) {
      this.endErr = false;
      if (this.form.start_date && moment(date).isBefore(moment(this.form.start_date))) {
        swal(
          "",
          "La fecha de finalización no puede ser antes la de inicio, por favor vuelva a seleccionarla"
        );
        this.endErr = true;
        this.init = "";
        return;
      }
      this.form.end_date = date;
    },
    verify() {
      if (this.startErr) {
        document.querySelector(".datepicker-start").value = "";
        this.form.start_date = "";
      }

      if (this.endErr) {
        document.querySelector(".datepicker-end").value = "";
        this.form.end_end = "";
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
    _convertToFormData(){
        let formData = new FormData();
        formData.append('_method', 'POST');
        Object.getOwnPropertyNames(this.form).forEach((key, i) => {
            if(key != "__ob__"){
                let value = this.form[key]
                if(key === 'start_date' || key === 'end_date') {
                    value = moment(value).format('YYYY-MM-DD');
                }
                formData.append(key, value);                   
            }
        });
        formData.append('products', JSON.stringify(this.products));

        return formData;
    },
  },
  mounted() {
    this.url = urlBase;
    console.log(this.categories, 'categories')
    var startDate = document.querySelector(".datepicker-start");
    var endDate = document.querySelector(".datepicker-end");
    var instancesStart = M.Datepicker.init(startDate, {
      onSelect: this.startDate,
      minYear: minYear,
      onClose: this.verify,
      i18n: pickDateI18n,
      minDate: new Date(),
    });
    var instanceEnd = M.Datepicker.init(endDate, {
      minYear: minYear,
      onSelect: this.endDate,
      onClose: this.verify,
      i18n: pickDateI18n,
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
