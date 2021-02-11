<template id="template-product-index">
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1> Reposicion de Inventario </h1>
      </div>
    </div>
    <div class="row">
      <div class="col s12">
        <section class="table__content" v-show="options == 0">
          <div class="row">
            <div class="col s12 container-btn-add">
              <div class="btn-add-text">Agregar nuevo</div>
              <button class="btn-add" @click="goToCreate">
                <img :src="'img/icons/new-msg.png' | asset" alt class="img-responsive" />
              </button>
            </div>
            <div class="col s12">
              <div class="row">
                <div class="col s3">
                  <input
                    type="text"
                    class="datepicker-start browser-default input-impegno"
                    name="start"
                    placeholder="Fecha de inicio"
                  />
                </div>
                <div class="col s3">
                  <input
                    type="text"
                    class="datepicker-end browser-default input-impegno"
                    name="end"
                    placeholder="Fecha de finalización"
                  />
                </div>
                <div class="col s3">
                  <select name="type" id="" v-model="filterForm.type">
                    <option value="">Todos</option>
                    <option value="0">Entrada</option>
                    <option value="1">Salida</option>
                  </select>
                </div>
                <div class="col s3">
                  <button class="btn btn-success" @click="_productsFilter()">
                    Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="col s12">
              <new-table-byte
                :setSearchValue="value => this.filterForm.search = value"
                :searchValue="this.filterForm.search"
                :setPageValue="page => _setPage(page)"
                :pageValue="this.page"
                :pages="this.pages"
                :filtersData="{
                    ...filterForm,
                }"
                :set-table="dataTable"
                :load="params => _load(params)"
                :filters="['productName', 'existing', 'modified', 'final', 'userName']"
                :export-file="true"
                :export-url="'admin/replenishment/export'"
                :export-title="'Reposicion-de-inventario'"
                :export-pdf-url="'admin/replenishment/pdf'"
              >
                <table-row slot="table-head" slot-scope="{ item }">
                  <table-head>#</table-head>
                  <table-head>Usuario</table-head>
                  <table-head>Producto</table-head>
                  <table-head>Presentacion</table-head>
                  <table-head>Tipo</table-head>
                  <table-head>Cantidad original</table-head>
                  <table-head>Cantidad modificada</table-head>
                  <table-head>Cantidad final</table-head>
                  <table-head>Fecha</table-head>
                  <table-head>Acciones</table-head>
                </table-row>
                <table-row slot="table-row" slot-scope="{ item }">
                  <table-cell> {{item.id}} </table-cell>
                  <table-cell> {{item.user.name}} </table-cell>
                  <table-cell> {{item.presentation.product.es_name}} </table-cell>
                  <table-cell> {{item.presentation.presentation}} {{item.presentation && item.presentation.unit && getUnit(item.presentation.unit) || ''}} </table-cell>
                  <table-cell> {{item.type == 0 ? 'Entrada' : 'Salida'}} </table-cell>
                  <table-cell> {{item.existing}} </table-cell>
                  <table-cell> {{item.modified}} </table-cell>
                  <table-cell> {{item.final}} </table-cell>
                  <table-cell> {{_getDate(item.created_at)}} </table-cell>
                  <table-cell>
                    <a href="#!" class="btn-action" @click="_view(item)" title="Ver">
                      <img :src="'img/icons/ico-ver.png' | asset" alt class="img-responsive" />
                    </a>
                  </table-cell>
                </table-row>
                <table-row slot="empty-rows">
                  <table-cell colspan="6">No se encontraron registros.</table-cell>
                </table-row>
              </new-table-byte>
            </div>
          </div>
        </section>
        <section v-if="options == 1" class="create-form">
            <div class="col s12 text-left button-container" style="display:flex; jus">
              <button
                class="btn btn-success"
                @click="goBack">
                Volver
              </button>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col s6">
                      <label for="product">Producto</label>
                      <model-select
                        :options="selectProducts"
                        v-model="form.product"
                        placeholder="Seleccione"
                        @change="changeProduct()"></model-select>
                    </div>

                    <div class="col s6" v-if="actualProduct && actualProduct.colors && actualProduct.colors[0] && actualProduct.colors[0].amounts.length > 1">
                        <label for="presentation">Presentacion</label>
                        <select name="presentation"
                          v-model="form.presentation"
                          @change="changePresentation()">
                            <option value="" disabled>Seleccione</option>
                            <option
                              v-for="element in actualProduct.colors[0].amounts"
                              :key="element.amount_id"
                              :value="element.amount_id">
                                {{element.presentation}}{{getUnit(element.unit)}}
                            </option>
                        </select>
                    </div>

                    <div class="col s6">
                        <label for="type">Tipo de Reposicion</label>
                        <select name="type" v-model="form.type" @change="_changeModifyAmount()">
                            <option value="" disabled>Seleccione</option>
                            <option value="0">Entrada</option>
                            <option value="1">Salida</option>
                        </select>
                    </div>
                    <div class="col s6">
                        <label for="existing">Cantidad en existencia</label>
                        <input name="existing" type="text" :value="form.existing" readonly>
                    </div>
                    <div class="col s6">
                        <label for="modified">Cantidad de Reposicion</label>
                        <input type="text" name="modified" v-model="form.modified" @keyup="_changeModifyAmount()" @change="_changeModifyAmount()">
                    </div>
                    <div class="col s6">
                        <label for="final">Cantidad Total</label>
                        <input type="text" name="final" :value="form.final" readonly>
                    </div>
                    <div class="col s12" st>
                        <label for="reason">Razon</label>
                        <textarea name="reason" cols="30" rows="6" v-model="form.reason"></textarea>
                    </div>
                    <div class="col s12 text-center button-container">
                      <button
                        class="btn btn-success"
                        @click="_submit()"
                        :disabled="sendForm || !form.modified || form.modified && parseInt(form.modified) <= 0">
                        Guardar
                      </button>
                    </div>
                    <div class="col s12">
                      <div class="progress" v-if="sendForm">
                          <div class="indeterminate"></div>
                      </div>
                    </div>
                </div>
            </div>
        </section>
      </div>
    </div>
    <byte-modal v-on:pressok="modal.action" :confirm="modal.type.confirm" v-if="modal.data">
        <div class="text-center">
          <div class="modal-content">
            <h3>Ver Reposicion </h3>
            <p> <strong>Fecha:</strong> {{_getDate(modal.data.created_at)}} </p>
            <p> <strong>Usuario:</strong> {{modal.data.userName}} </p>
            <p> <strong>Nro. de Reposición:</strong> {{modal.data.id}} </p>
            <p> <strong>Producto:</strong> {{modal.data.productName}} </p>
            <p v-if="modal.data && modal.data.presentation && modal.data.presentation.presentation"> <strong>Presentacion:</strong> {{modal.data.presentation.presentation}} {{modal.data.presentation && modal.data.presentation.unit && getUnit(modal.data.presentation.unit)}} </p>
            <p> <strong>Tipo:</strong> {{modal.data.type == 0 ? 'Entrada' : 'Salida'}} </p>
            <p> <strong>Cantidad original:</strong> {{modal.data.existing}} </p>
            <p> <strong>Cantidad modificada:</strong> {{modal.data.modified}} </p>
            <p> <strong>Cantidad final:</strong> {{modal.data.final}} </p>
            <p style="white-space: pre;"> <strong>Razón:</strong> {{modal.data.reason}} </p>
          </div>
        </div>
    </byte-modal>
  </section>
</template>

<style lang="scss">
strong{
  color: black;
  font-weight: bold;
}
.img-products {
  height: 80%;
  width: 80%;
  object-fit: contain;
}
select{
  display: block;
}
select.datepicker-select{
  display: none;
}
.ui.search.selection.dropdown>input.search{
  height: auto;
}
.create-form{
    select{
      display: block;
    }
    textarea{
      resize: vertical;
    }
    .button-container{
      display: flex;
      align-items: center;
      justify-content: center;
      padding: .4rem;
    }
}
table{
  text-align: center;
}
</style>

<script>
import { unities } from "../../constants";
// import { ModelSelect } from 'vue-search-select'
export default {
  template: "#template-product-index",
  props: {
    products: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      dataTable: [],
      selectProducts: [],
      fullProducts: [],
      fullReplenishments: [],
      options: 0,
      page: 1,
      pages: 1,
      actualProduct: {},
      sendForm: false,
      search: '',
      iconExpand: {
        open: 'expand_more',
        close: 'expand_less'
      },
      filterForm:{
        start: '',
        end: '',
        type: '',
        search: ''
      },
      form:{
        product: '',
        presentation: '',
        presentation_object: {},
        type: '',
        existing: 0,
        modified: 0,
        final: 0,
        reason: ''
      },
      modal: {
        init: {},
        title: "",
        type: {
          confirm: false,
          action: "view"
        },
        action: {},
        data: {
          collections: {},
          categories: {},
          subcategories: {},
          designs: {}
        }
      }
    };
  },
  watch:{
    'form.product': function(val, oldVal) {
          this.changeProduct()
      }
  },
  computed: {
    filteredItem() {
      const item = this.products.find(element => {
        return element.name.toLowerCase().includes(this.search.toLowerCase())
      })
      const firstItem = item.colors[0].amounts[0]
      this.actualProduct = item
      this.form = {
        ... this.form,
        presentation: firstItem.amount_id,
        presentation_object: firstItem,
        existing: firstItem.amount,
        modified: 0,
        final: firstItem.amount
      }
      return item
    }
  },
  methods: {
    _view(item) {
      this.modal.type.confirm = false;
      this.modal.type.action = "view";
      this.modal.data = item;
      this.modal.init.open();
    },
    changePresentation(){
      const item = this.actualProduct.colors[0].amounts.find(element => element.amount_id == this.form.presentation)
      if(item){
        this.form = {
          ... this.form,
          presentation_object: item,
          existing: item.amount,
          final: item.amount,
          modified: 0,
        }
      }
    },
    changeProduct(){
        const actualItem = this.products.find(element => element.id == this.form.product)
        if(actualItem){
            const firstItem = actualItem.colors[0].amounts[0]
            this.actualProduct = actualItem
            this.form = {
              ... this.form,
              presentation: firstItem.amount_id,
              presentation_object: firstItem,
              existing: firstItem.amount,
              modified: 0,
              final: firstItem.amount
            }
        }
    },
    _changeModifyAmount(){
      if(this.form.type){
        if(typeof this.form.existing == 'number' && typeof parseInt(this.form.modified) == 'number'){
          let value = 0;
          if(this.form.type == 0)
            value = parseInt(this.form.existing) + parseInt(this.form.modified)
          else
            value = parseInt(this.form.existing) - parseInt(this.form.modified)
  
          if((this.form.modified >= this.form.existing) && (this.form.type != 0)){
            value = this.form.existing
            this.form.modified = this.form.existing
            value = 0
          }
          this.form.final = value
        }else{
          this.form.modified = 0
        }
      }
    },
    getUnit(unit) {
      return unities.find(u => u.id == unit).name;
    },
    _setPage(page){
        this.page = page
        this._productsFilter()
    },
    _productsFilter(){
      showLoading()
      axios.post('admin/replenishment/filter?page='+this.page, this.filterForm)
        .then(res => {
          if(res.data.reps){
            let data = [...res.data.reps.data]
            if(data && data.length > 0){
              data.forEach((element, i) => {
                element.productName = element.presentation.product.es_name
                element.userName = element.user.name
              })
              this.dataTable = [...data]
              this.pages = res.data.reps.last_page
            }
          }
        })
        .catch(err => {
          console.log(err, 'err')
        })
        .finally(() => {
          quiLoading()
        })
    },
    _submit(){
      this.sendForm = true
      axios.post('admin/replenishment', this.form)
        .then(res => {
          swal(res.data.msg)
          if(!res.data.error){
            let products = [... this.products]
            const id = this.form.presentation_object && this.form.presentation_object.product_color_id || this.form.product
            const productIndex = products.findIndex(element => element.id == this.actualProduct.id)
            if(productIndex != -1){
              let product = products[productIndex];
              if(product){
                const presentationIndex = product.colors[0].amounts.length > 1 ?  product.colors[0].amounts.findIndex(element => element.amount_id == this.form.presentation) : 0
                if(presentationIndex != -1){
                  let presentation = product.colors[0].amounts[presentationIndex]
                    presentation.amount = this.form.final
                  this.fullProducts = products
                }
              }
            }
            this.form = {
              product: '',
              presentation: '',
              presentation_object: {},
              type: '',
              existing: 0,
              modified: 0,
              final: 0,
              reason: ''
            }
            if(res.data.item){
              this.fullReplenishments.push(res.data.item)
              // this._load()
            }
            this.options = 0
            this._productsFilter()
          }
        })
        .catch(err => {
          console.log('>>: err > ', err)
        })
        .finally(() => {
          this.sendForm = false
        })
    },
    startDate(date) {
      this.initErr = false;
      if (this.filterForm.end && moment(date).isAfter(moment(this.filterForm.end))) {
        swal(
          "",
          "La fecha de inicio no puede ser superior a la de finalización,  por favor vuelva a seleccionarla"
        );
        this.initErr = true;
        return false;
      }
      this.filterForm.start = date;
    },
    endDate(date) {
      this.endErr = false;
      if (this.filterForm.start && moment(date).isBefore(moment(this.filterForm.start))) {
        swal(
          "",
          "La fecha de finalización no puede ser antes la de inicio, por favor vuelva a seleccionarla"
        );
        this.endErr = true;
        this.filterForm.end = "";
        return false;
      }
      this.filterForm.end = date;
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
    _getDate(date){
      return moment(date).format('DD-MM-YYYY HH:mm A')
    },
    _load(params = null){
      let data = [...this.fullReplenishments]
      this.fullProducts = [... this.products]
      if(data && data.length > 0){
        data.forEach((element, i) => {
          element.productName = element.presentation.product.es_name
          element.userName = element.user.name
        })
        this.dataTable = [...data]
      }
      this.selectProducts = this.fullProducts.map(element => {
        return {
          text: element.name.normalize('NFD')
            .replace(/([aeio])\u0301|(u)[\u0301\u0308]/gi,"$1$2")
            .normalize(),
          value: element.id
        }
      })
    },
    _mountDatepickers() {
        setTimeout(() => {
        M.Datepicker.init(document.querySelector('.datepicker-start'), {
            minYear: minYear,
            maxYear: maxYear,
            format: "yyyy-mm-dd",
            onSelect: this.startDate,
            onClose: this.verify,
            i18n: pickDateI18n
        });
        M.Datepicker.init(document.querySelector('.datepicker-end'), {
            minYear: minYear,
            maxYear: maxYear,
            format: "yyyy-mm-dd",
            onSelect: this.endDate,
            onClose: this.verify,
            i18n: pickDateI18n
        });
      }, 100);
    },
    goToCreate() {
      this.options = 1;
    },
    goBack() {
      this.options = 0;
      this._mountDatepickers();
    }
  },

  mounted() {
    this._productsFilter()
    this.modal.init = M.Modal.init(document.querySelector(".modal"));
    this._load()
    this._mountDatepickers()
  }
};
</script>
