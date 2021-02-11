<template id="template-product-index">
  <section class="container-fluid">
    <div class="row">
      <div class="col s12 center-align">
        <h1 v-if="options == 0">Productos</h1>
        <h1
          v-if="options == 1 || options == 2"
        >{{ options == 1 ? 'Agregar' : 'Actualizar'}} Producto</h1>
      </div>
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
            <div class="col s12 m3">
              <label class="label-impegno">Tipo</label>
              <select
                name
                id
                v-model="typeProduct"
                class="browser-default"
              >
                <option value>Todos</option>
                <option value="0">Simple</option>
                <option value="1">Variable</option>
              </select>
            </div>
            <div class="col s12 m3">
              <label class="label-impegno">Categoría</label>
              <select name="" id="" v-model="category" class="browser-default" @change="_changeCategory">
                  <option value="">Todos</option>
                  <option :value="item.id" v-for="item in categories" :key="item.id"> {{item.name}} </option>
              </select>
            </div>
            <div class="col s12 m3">
              <label class="label-impegno">Subcategoria</label>
              <select name="" id="" v-model="subcategory" class="browser-default">
                  <option value="">Todos</option>
                  <option :value="item.id" v-for="item in subcategories" :key="item.id"> {{item.name}} </option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m3">
              <label class="label-impegno">Estado</label>
              <select name="" id="" v-model="status" class="browser-default">
                  <option value="">Todos</option>
                  <option value="0">Desactivado</option>
                  <option value="1">Activo</option>
              </select>
            </div>
            <div class="col s12 m3">
              <label class="label-impegno">Existencia</label>
              <select name="" id="" v-model="inventory" class="browser-default">
                  <option value="">Todos</option>
                  <option value="0">Agotado</option>
                  <option value="1">Con existencia</option>
                  <option value="2">Con poca existencia</option>
              </select>
            </div>
            <div class="col s12 m3 btn-search-margin-top">
              <button type="button" class="btn btn-success" @click="_load()">
                <span class="v-align-up cursor">Buscar</span>
              </button>
            </div>
          </div>
          <div v-if="checkActive && checkActive.length > 0">
            <div class="col s6 m6">
              <button type="button" class="btn btn-success" @click="_active(1)">
                <span class="v-align-up cursor">Activar Seleccionados</span>
              </button>
            </div>
            <div class="col s6 m6">
              <button type="button" class="btn btn-success" @click="_active(0)">
                <span class="v-align-up cursor">Desactivar Seleccionados</span>
              </button>
            </div>
          </div>
          <new-table-byte
            :setSearchValue="value => this.search = value"
            :searchValue="this.search"
            :setPageValue="page => _setPage(page)"
            :pageValue="this.page"
            :pages="this.pages"
            :filtersData="{ 
                typeProduct: this.typeProduct, 
                category: this.category,
                subcategory: this.subcategory,
                status: this.status,
                inventory: this.inventory,
                search: this.search || ''
            }"
            :set-table="dataTable"
            :filters="['name']"
            :export-file="true"
            :export-url="'admin/products/export'"
            :export-title="'Reporte-Productos'"
            :import-url="'admin/products/import'"
          >
            <table-row slot="table-head" slot-scope="{ item }">
              <table-head class="photo-th">Foto</table-head>
              <table-head>Categoría</table-head>
              <table-head>Código #</table-head>
              <table-head>Producto</table-head>
              <table-head>Existencia</table-head>
              <table-head>Umbral</table-head>
              <table-head>Impuesto</table-head>
              <table-head>Costo</table-head>
              <table-head>Precio</table-head>
              <table-head>Ganancia</table-head>
              <table-head>Porcentaje</table-head>
              <table-head>Registro</table-head>
              <table-head>Modificación</table-head>
              <table-head>Acciones</table-head>
              <table-head class="text-center">Destacado</table-head>
              <table-head class="text-center" style="text-align: left;">
                Seleccionar
                <input type="checkbox" v-model="selectAll" @click="select" @change="checkbox">
              </table-head>
            </table-row>

            <table-row slot="table-row" slot-scope="{ item }">
              <table-cell>
                <img :src="item.image_url" alt class="img-responsive"/>
              </table-cell>
              <table-cell>{{ (item.categories.name) }}</table-cell>
              <table-cell>{{ item.id }}</table-cell>
              <table-cell>{{ (item.name) }}</table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.expand" style="display: flex; align-items: center;">
                        <strong>
                          {{element.presentation }} 
                          {{
                            item.presentations && 
                            item.presentations[index] && 
                            item.presentations[index].unit && 
                            getUnit(item.presentations[index].unit) || ''}}:
                          </strong> {{element.amount == 0 ? 'Agotado' : element.amount}}
                        <i v-if="index == 0" @click="_expandInfo(item, 'expand')" class="material-icons"> {{!item.expand ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> {{item.presentations[0].amount === 0 ? 'Agotado' : item.presentations[0].amount}} </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.umbralExpand" style="display: flex; align-items: center;">
                        <strong>{{element.presentation }} {{item.presentations && item.presentations[index] && item.presentations[index].unit && getUnit(item.presentations[index].unit) || ''}}:</strong> {{element.umbral}}
                        <i v-if="index == 0" @click="_expandInfo(item, 'umbralExpand')" class="material-icons"> {{!item.umbralExpand ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> {{item.presentations[0].umbral}} </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                {{ item.taxe && item.taxe.name || 'Excento' }}
              </table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.costExpand" style="display: flex; align-items: center;">
                        <strong>{{element.presentation }} {{item.presentations && item.presentations[index] && item.presentations[index].unit && getUnit(item.presentations[index].unit) || ''}}: </strong>&nbsp; ${{element.cost}}
                        <i v-if="index == 0" @click="_expandInfo(item, 'costExpand')" class="material-icons"> {{!item.costExpand ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> ${{item.presentations[0].cost}} </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.priceExpand" style="display: flex; align-items: center;">
                        <strong>{{element.presentation }} {{item.presentations && item.presentations[index] && item.presentations[index].unit && getUnit(item.presentations[index].unit) || ''}}: </strong>&nbsp; ${{element.price}}
                        <i v-if="index == 0" @click="_expandInfo(item, 'priceExpand')" class="material-icons"> {{!item.priceExpand ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> ${{item.presentations[0].price}} </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.utilityExpand" style="display: flex; align-items: center;">
                        <strong>{{element.presentation }} {{item.presentations && item.presentations[index] && item.presentations[index].unit && getUnit(item.presentations[index].unit) || ''}}: </strong>&nbsp; {{(element.price - element.cost) | USD}}
                        <i v-if="index == 0" @click="_expandInfo(item, 'utilityExpand')" class="material-icons"> {{!item.utilityExpand ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> {{(item.presentations[0].price - item.presentations[0].cost) | USD}} </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                <div v-if="item.presentations.length > 0">
                  <div v-if="item.presentations.length > 1">
                      <p v-for="(element, index) in item.presentations" :key="index" v-show="index == 0 || item.percent" style="display: flex; align-items: center;">
                        <strong>{{element.presentation }} {{item.presentations && item.presentations[index] && item.presentations[index].unit && getUnit(item.presentations[index].unit) || ''}}: </strong>&nbsp; {{_formatMiles(((element.price - element.cost) / element.cost) * 100)}}%
                        <i v-if="index == 0" @click="_expandInfo(item, 'percent')" class="material-icons"> {{!item.percent ? iconExpand.open: iconExpand.close}} </i>
                      </p>
                  </div>
                  <div v-else> {{_formatMiles(((item.presentations[0].price - item.presentations[0].cost) / item.presentations[0].cost) * 100 )}}% </div>
                </div>
                <div v-else>
                  Sin presentaciones
                </div>
              </table-cell>
              <table-cell>
                {{_getDate(item.created_at)}}
              </table-cell>
              <table-cell>
                {{_getDate(item.updated_at)}}
              </table-cell>
              <table-cell class="actions">
                <a href="#!" class="btn-action" @click="_view(item)" title="Ver">
                  <img :src="'img/icons/ico-ver.png' | asset" alt class="img-responsive" />
                </a>

                <a href="#!" class="btn-action" @click="_edit(item)" title="Editar">
                  <img :src="'img/icons/ico-editar.png' | asset" alt class="img-responsive" />
                </a>

                <a
                  href="#!"
                  class="btn-action"
                  @click="_confirm(item, 'postear')"
                  :title="`${item.status == '1' ? 'Desactivar': 'Activar'}`"
                >
                  <img
                    :src="'img/icons/ico-toggle-on.svg' | asset"
                    alt
                    class="img-responsive"
                    style="width: 36px; margin: 0;"
                    v-if="item.status == '1'"
                  />
                  <img
                    :src="'img/icons/ico-toggle-off.svg' | asset"
                    alt
                    class="img-responsive"
                    style="width: 36px; margin: 0;"
                    v-if="item.status == '0'"
                  />
                </a>

                <a href="#!" class="btn-action" @click="_confirm(item, 'delete')" title="Eliminar">
                  <img :src="'img/icons/ico-eliminar.png' | asset" alt class="img-responsive" />
                </a>
              </table-cell>
              <table-cell>
                <div class="text-center" style="display: flex; justify-content: center;" >
                  <a href="#!" class="btn-action" @click="_confirm(item, 'pro')" title="Producto PRO">
                    <img
                      :src="'img/icons/ico-toggle-on.svg' | asset"
                      alt
                      class="img-responsive"
                      style="width: 36px; margin: 0;"
                      v-if="item.pro == '1'"
                    />
                    <img
                      :src="'img/icons/ico-toggle-off.svg' | asset"
                      alt
                      class="img-responsive"
                      style="width: 36px; margin: 0;"
                      v-if="item.pro == '0'"
                    />
                  </a>
                </div>
              </table-cell>
              <table-cell class="text-center" style="text-align: center;">
                <input type="checkbox" :value="item.id" v-model="checkActive" >
              </table-cell>
            </table-row>
            

            <table-row slot="empty-rows">
              <table-cell colspan="3">No se encontraron registros.</table-cell>
            </table-row>
          </new-table-byte>
        </section>

        <product-form
          v-if="options == 1"
          @back="_resetView"
          @reload="_updateData"
          :categories="categories"
          :designs="designs"
          :collections="collections"
          :taxes="taxes"
        ></product-form>
        <product-edit
          v-if="options == 2"
          @back="_resetView"
          :data="form"
          :categories="categories"
          :designs="designs"
          :collections="collections"
          :taxes="taxes"
          @reload="_updateData"
        ></product-edit>
      </div>
    </div>
    <byte-modal v-on:pressok="modal.action" :confirm="modal.type.confirm">
      <template v-if="modal.type.action == 'delete'">
        <div class="container-confirmation">
          <div class="confimation__icon">
            <i class="material-icons">error_outline</i>
          </div>
          <div class="confirmation__text">
            <h5>
              ¿ Realmente deseas
              <b>Eliminar</b> este Producto ?
            </h5>
          </div>
        </div>
      </template>

      <template v-else-if="modal.type.action == 'postear'">
        <div class="container-confirmation">
          <div class="confimation__icon">
            <i class="material-icons">error_outline</i>
          </div>
          <div class="confirmation__text">
            <h5>
              ¿ Realmente deseas
              <b>{{ modal.data.status == 1 ? 'Desactivar ' : 'Publicar' }}</b> este Producto ?
            </h5>
          </div>
        </div>
      </template>

      <template v-else-if="modal.type.action == 'pro'">
        <div class="container-confirmation">
          <div class="confimation__icon">
            <i class="material-icons">error_outline</i>
          </div>
          <div class="confirmation__text">
            <h5>
              ¿Realmente deseas
              <b>{{ modal.data.pro != 1 ? 'convertir en Producto PRO?' : 'convertir en Producto Normal?' }}</b>
            </h5>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="col s12">
          <h3>Información del Producto</h3>
        </div>

        <div class="col s12 m6">
          <span>Producto (Español):</span>
          {{ modal.data.name }}
        </div>
        <!--<div class="col s12 m6">
          <span>Producto (Ingles):</span>
          {{ modal.data.name_english }}
        </div>-->

        <div class="col s12 m6">
          <span>Descripción (Español):</span>
          {{ modal.data.description }}
        </div>

        <!--<div class="col s12 m6">
          <span>Descripción (Ingles):</span>
          {{ modal.data.description_english }}
        </div> -->

        <div class="col s12 m6">
          <span>Impuesto:</span>
          {{ modal.data.taxe ? modal.data.taxe.name : 'Exento de IVA' }}
        </div>

        <div class="col s12" v-if="modal.data.variable != 1">
          <h3>Precio y cantidad</h3>
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1">
          <span>Precio:</span>
          ${{ modal.data.price_1 }}
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1 && modal.data.presentations">
          <span>Cantidad:</span>
          {{ modal.data.presentations[0].amount }}
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1 && modal.data.presentations">
          <span>Minimo de venta:</span>
          {{ modal.data.presentations[0].min }} unid.
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1 && modal.data.presentations">
          <span>Máximo de venta:</span>
          {{ modal.data.presentations[0].max }} unid.
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1 && modal.data.presentations">
          <span>Umbral:</span>
          {{ modal.data.presentations[0].umbral }}
        </div>

        <div class="col s12 m6" v-if="modal.data.variable != 1 && modal.data.presentations">
          <span>Costo:</span>
          ${{ modal.data.presentations[0].cost }}
        </div>

        <div class="col s12" v-if="modal.data.variable == 1">
          <h3>Presentaciones</h3>
        </div>

        <div
          :key="presentation.amount_id"
          v-for="presentation in modal.data.presentations"
          v-if="modal.data.variable == 1"
        >
          <div class="row" style="margin-botton: 5px; border-bottom: 1px solid #e1e1e1;">
            <div class="col s12 m4" style=" padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Presentacion:</b>
              </span>
              {{ presentation.presentation }} {{ getUnit(presentation.unit) }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Cantidad:</b>
              </span>
              {{ presentation.amount }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Precio:</b>
              </span>
              ${{ presentation.price }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Costo:</b>
              </span>
              ${{ presentation.cost }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Min Venta:</b>
              </span>
              {{ presentation.min }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Max. Venta:</b>
              </span>
              {{ presentation.max }}
            </div>
            <div class="col s12 m4" style="padding-bottom: .5rem; padding-top: .4rem;">
              <span>
                <b>Umbral:</b>
              </span>
              {{ presentation.umbral }}
            </div>
          </div>
        </div>

        <!-- <div class="col s12 m6">
                    <span>Precio (Mayor):</span> {{ modal.data.coin == "1" ? '$' : "Bs." }}{{ modal.data.price_2 }}
        </div>-->

        <!-- <div class="col s12 m6">
                    <span>Tipo producto: </span> {{ modal.data.varibla == "1" ? "Variable" : "Simple" }}
        </div>-->

        <!-- <div class="col s12">
                    <h3>Diseño</h3>
                </div>

                <div class="col s12 m6">
                    <span>Diseño (Español): </span> {{ modal.data.designs.name }}
                </div>

                <div class="col s12 m6">
                    <span>Diseño (Ingles): </span> {{ modal.data.designs.name_english }}
        </div>-->

        <!-- <div class="col s12">
                    <h3>Colecciones</h3>
                </div>

                <div class="col s12 m6">
                    <span>Colecciones (Español): </span> {{ modal.data.collections.name }}
                </div>

                <div class="col s12 m6">
                    <span>Colecciones (Ingles): </span> {{ modal.data.collections.name_english }}
                </div>

                <div class="col s12">
                    <h3>Colores</h3>
                </div>

                <div class="col s12" v-for="(color, i) in modal.data.colors" :key="'color-' + i">
                    <div class="col s12 m6 no-padding">
                        <span>Color (Español): </span> {{ color.name }}
                    </div>

                    <div class="col s12 m6 no-padding">
                        <span>Color (Ingles): </span> {{ color.name_english }}
                    </div>
        </div>-->

        <div class="col s12">
          <h3>Categoría</h3>
        </div>

        <div class="col s12 m6">
          <span>Categoría:</span>
          {{ modal.data.categories.name }}
        </div>

        <div class="col s12" v-if="modal.data.subcategories">
          <h3>Subcategoría</h3>
        </div>

        <div class="col s12 m6" v-if="modal.data.subcategories">
          <span>Subcategoría:</span>
          {{ modal.data.subcategories.name }}
        </div>

        <!-- <div class="col s12 m6" v-if="modal.data.subcategories">
                    <span>Subcategoría (Ingles): </span> {{ modal.data.subcategories.name_english }}
        </div>-->

        <div class="col s12">
          <h3>Imagenen Principal</h3>
        </div>

        <div class="col s12 m6">
          <img class="img-products" :src="modal.data.image_url" alt />
        </div>

        <div class="col s12" v-if="modal.data.images && modal.data.images.length > 1">
          <h3>Imagenenes Secundarias</h3>
        </div>

        <div
          class="col s12 m6"
          v-for="(img, i) in modal.data.images"
          :key="'img-main' + i"
          v-if="img.main == 0"
        >
          <img class="img-products" :src="`img/products/${img.file}` | asset" alt />
        </div>
      </template>
    </byte-modal>
  </section>
</template>

<style lang="scss">
.img-products {
  height: 80%;
  width: 80%;
  object-fit: contain;
}
table{
  display: grid;
  overflow-x:auto;
  // .photo-th{
  //   @media (min-width: 768px) {
  //     min-width: 4.3rem;
  //   }
  // }
  td:not(.actions), th{
    min-width: 10rem;
    max-width: 10rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  td, th{
    font-size: 1rem;
    @media (max-width: 1400px) {
      font-size: .8rem;
    }
    img {
      width: 8rem !important;
      @media (max-width: 1400px) {
        width: 4rem !important;
      }
    }
    a{
      img{
        vertical-align: middle;
        width: 30px !important;
        margin: 2px 4px !important;
        @media (max-width: 1400px) {
          width: 15px !important;
          margin: 0px 2px !important;
        }
      }
    }
  }
  tbody{
    white-space: nowrap;
  }
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
    position: relative;
    opacity: 1;
    pointer-events: auto;
    left: auto;
}

.btn-search-margin-top{
  margin-top: 2.2rem;
}
</style>

<script>
import { unities } from "../../constants";
import ProductForm from "./ProductForm";
import ProductEdit from "./ProductEdit";
export default {
  template: "#template-product-index",
  components: { ProductForm, ProductEdit },
  props: {
    categories: {
      type: Array,
      default: []
    },

    designs: {
      type: Array,
      default: []
    },

    collections: {
      type: Array,
      default: []
    },
    taxes: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      options: 0,
      form: {},
      dataTable: [],
      productos: [],
      inventory: '',
      filters: [],
      typeProduct: "",
      category: '',
      subcategory: '',
      search:'',
      page: 1,
      pages: 1,
      subcategories: [],
      status: "",
      expand: true,
      iconExpand: {
        open: 'expand_more',
        close: 'expand_less'
      },
      checkActive: [],
      selectAll: false,
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

  methods: {
    _resetView(option) {
      this.options = option;
    },

    _view(item) {
      this.modal.type.confirm = false;
      this.modal.type.action = "view";
      this.modal.data = item;
      this.modal.init.open();
    },

    _confirm(item, action) {
      this.modal.type.confirm = true;
      this.modal.type.action = this._delete;
      this.modal.data = item;

      if (action == "delete") {
        this.modal.type.action = "delete";
        this.modal.action = this._delete;
      }

      if (action == "postear") {
        this.modal.type.action = "postear";
        this.modal.action = this._postear;
      }

      if (action == "pro") {
        this.modal.type.action = "pro";
        this.modal.action = this._pro;
      }

      if (action == "active") {
        this.modal.type.action = "activar";
        this.modal.action = this._active;
      }

      this.modal.init.open();
    },
    _filterProduct(products) {
      const typeProduct = this.typeProduct;
      let rawProducts = typeProduct == ""
          ?
            [...products]
          : 
            [...products.filter(product => product.variable == parseInt(typeProduct))];
      
      return rawProducts.map(d => ({
          ...d,
          presentations: d.colors[0].amounts
        })
      );
    },
    _changeStatus(rawProducts) {
      let products = [...rawProducts]
      if(this.status && typeof this.status == 'string') {
        return products.filter(element => element.status === this.status).map(d => ({
          ...d,
          presentations: d.colors[0].amounts
        }));
      } else {
        return products.map(d => ({
          ...d,
          presentations: d.colors[0].amounts
        }));
      }
    },
    _formatMiles(n) {
      let c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;

      return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    },
    _edit(item) {
      this.options = 2;
      this.form = item;
    },

    _delete() {
      let index = this.dataTable.findIndex(e => {
        return e.id == this.modal.data.id;
      });

      this.modal.init.close();

      axios
        .delete(`admin/products/${this.modal.data.id}`)
        .then(res => {
          this.dataTable.splice(index, 1);
          this._showAlert(res.data.message, "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },
    _cutStr(str){
      let val = str
      if(val.length >= 12){
        val = val.substr(0, 12)+'...'
      }
      return val
    },
    _active(status) {

      this.modal.init.close();
      let data = {
        checks: this.checkActive,
        status
      }
      axios
        .post(`admin/products/active`, data)
        .then(res => {
          this.checkActive.map(id => {
            const item = this.dataTable.find(element => element.id == id)
            if(item){
              item.status = status.toString()
            }
          });
          this._showAlert(res.data.msg, "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },

    _postear() {
      let index = this.dataTable.findIndex(e => {
        return e.id == this.modal.data.id;
      });

      this.modal.init.close();

      axios
        .post(`admin/product/postear/${this.modal.data.id}`)
        .then(res => {
          this.dataTable[index].status = res.data.status;
          swal("", res.data.message, "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },

    _pro() {
      let index = this.dataTable.findIndex(e => {
        return e.id == this.modal.data.id;
      });

      this.modal.init.close();

      axios
        .post(`admin/product/pro/${this.modal.data.id}`)
        .then(res => {
          this.dataTable[index].pro = res.data.pro;
          swal("", res.data.message, "success");
        })
        .catch(err => {
          this._showAlert("Disculpa, ha ocurrido un error", "error");
        });
    },

    _updateData() {
      axios
        .get("admin/products-all")
        .then(res => {
          this.dataTable = [
            ...res.data.map(d => ({ ...d, presentations: d.colors[0].amounts }))
          ];
        })
        .catch(err => {
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
    getUnit(unit) {
      let val = unities.find(u => u.id == unit)
      return val && val.name || '';
    },
    _updateFromCategory(rawProducts){
      let products = rawProducts.map(d => ({
          ...d,
          presentations: d.colors[0].amounts
        })
      )
      if(this.category)
        products = products.filter(element => element.category_id == this.category)
      if(this.subcategory)
        products = products.filter(element => element.subcategory_id == this.subcategory)
      
      return products
    },
    _changeCategory(){
      const value = this.category
      if(value){
        const category = this.categories.find(element => element.id == value)
        if(category){
          this.subcategories = category.subcategories
        }
      } else {
        this.subcategories = [];
        this.subcategory = '';
      }
    },
    _changeInvetory(rawProducts){
      let products = [...rawProducts].map(d => ({
          ...d,
          presentations: d.colors[0].amounts
        }))
      if(this.inventory == '0'){
        let newProducts = []
        products.forEach((element, i) => {
          const spent = element.colors[0].amounts.filter(element => element.amount === 0);
          if(spent && Array.isArray(spent) && spent.length > 0){
            const itemDtable = this.dataTable.find(_element => _element.id === element.id)
            let item = {...element}
              item.colors[0].amounts = spent
            if(itemDtable)
              item.presentations = itemDtable.presentations || []
            newProducts.push(item)
          }
        })
        products = newProducts
      }else if(this.inventory == '1'){
        let newProducts = []
        products.forEach((element, i) => {
          const availables = element.colors[0].amounts.filter(element => element.amount > 0 && element.amount > element.umbral)
          if(availables && Array.isArray(availables) && availables.length > 0){
            const itemDtable = this.dataTable.find(_element => _element.id === element.id)
            let item = {...element}
                item.colors[0].amounts = availables
            if(itemDtable)
              item.presentations = itemDtable.presentations || []
            newProducts.push(item)
          }
        })
        products = newProducts
      }else if(this.inventory == '2'){
        let newProducts = []
        products.forEach((element, i) => {
          const availables = element.colors[0].amounts.filter(element => element.amount > 0 && element.amount <= element.umbral)
          if(availables && Array.isArray(availables) && availables.length > 0){
            const itemDtable = this.dataTable.find(_element => _element.id === element.id)
            let item = {...element}
              item.colors[0].amounts = availables
            if(itemDtable)
              item.presentations = itemDtable.presentations || []
            newProducts.push(item)
          }
        })
        products = newProducts
      }
      return products
    },
    _getDate(value){
      return moment(value).format('DD-MM-YY hh:mm A')
    },
    _expandInfo(element, type){
      try {
        let data = [...this.dataTable]
        const index = data.findIndex(_element => _element.id == element.id)
        if(index != -1){
          let item = data[index]
          if(typeof item[type] === 'boolean'){
            item[type] = !item[type]
          }else{
            item[type] = true
          }
          data[index] = item
          this.dataTable = data
        }
      } catch (error) {
      }
    },
    select() {
      this.checkActive = [];
      if (!this.selectAll) {
        for (let i in this.dataTable) {
          this.checkActive.push(this.dataTable[i].id);
        }
      }
    },
    checkbox(){
    },
    _setAllFilters(){
      let products = [... this.products]
      if(this.typeProduct){
        products = this._filterProduct(products)
      }
      if(this.category){
        products = this._changeCategory(products)
      }
      if(this.status && typeof this.status == 'string'){
        products = this._changeStatus(products)
      }
      if(this.inventory && typeof this.inventory == 'string'){
        products = this._changeInvetory(products)
      }
      this.dataTable = products
    },
    _setPage(page){
        this.page = page
        this._load()
    },
    _load(){
      if (this.submitted) {
          return;//
      }
      this.submitted = true;
      showLoading()
      axios.post(`admin/products/get?page=${this.page}`, { 
          typeProduct: this.typeProduct, 
          category: this.category,
          subcategory: this.subcategory,
          status: this.status,
          inventory: this.inventory,
          search: this.search || ''
      })
          .then(res => {
              this.pages = res.data.products.last_page
              let products = res.data.products.data.map(d => ({
                  ...d,
                  presentations: d.colors[0].amounts
                })
              )
              this.dataTable = [... products];
          })
          .catch(err => {
              swal('', 'Algo ha sucedido', 'error')
              console.log(err)
          })
          .finally(() => {
              this.submitted = false;
              quiLoading()
          });
    }
  },

  mounted() {
    this._load()
    this.modal.init = M.Modal.init(document.querySelector(".modal"));
    // this.productos = [
    //   ...this.dataTable.map(d => ({ ...d, presentations: d.colors[0].amounts }))
    // ];
  }
};
</script>
