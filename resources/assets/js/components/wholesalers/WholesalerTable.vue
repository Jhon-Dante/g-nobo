<template id="template-wholesaler-index">
    <article>
        <div class="row">
            <div class="col s12 center-align">
                <h1>Colecciones Jurídicas</h1>
            </div>
        </div>

        <!-- TABLA DE REGISTROS -->
        <div class="row">
            <div class="col s12">
                <section class="table__content">
                    <div class="row">
                        <a href="#" class="col s12 container-btn-add" @click="setOption(2)">
                            <button class="btn-add">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nuevo
                            </div>       
                        </a>
                    </div>
                    <table-byte :set-table="wholesalers" :filters="[]">
                        <table-row slot="table-head">
                            <table-head class="th">Nombre </table-head>
                            <table-head>Cantidad</table-head>
                            <table-head>Precio</table-head>
                            <table-head>Moneda</table-head>
                            <table-head>Descripción</table-head>
                            <table-head class="head-actions">Acción</table-head>

                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>{{ item.quantity }}</table-cell>
                            <table-cell>{{ item.price }}</table-cell>
                            <table-cell>{{ item.coin == 1 ? 'Bs.' : 'USD' }}</table-cell>
                            <table-cell>{{ item.description }}</table-cell>
                            <table-cell class="icon-margin">

                                <a href="#!" class="btn-action" @click="_view(item)">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a class="btn-action" :href="`admin/wholesalers/${item.id}/edit` | asset">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'delete')">
                                    <img :src="'img/icons/ico-eliminar.png' | asset" alt="" class="img-responsive">
                                </a>

                            </table-cell>
                        </table-row>

                        <table-row slot="empty-rows">
                            <table-cell colspan="5">
                                No se encontraron registros.
                            </table-cell>
                        </table-row>

                    </table-byte>
                    
                    <!-- <byte-modal v-on:pressok="_delete" :confirm="modal.type.confirm">
                        <template v-if="modal.type.action == 'view'">
                            <div class="col s12">
                                <h3>Detalles del Blog</h3>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Nombre</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.name }}
                                </div>
                                <div class="col s12">
                                    <h3>Cantidad</h3>
                                </div>      
                                <div class="col s12">
                                    {{ modal.data.quantity }}
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Precio</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.price }}
                                </div>
                                <div class="col s12">
                                    <h3>Moneda</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.coin }}
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Nombre (Inglés)</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.name_english }}
                                </div>
                                <div class="col s12">
                                    <h3>Descripción (Inglés)</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.description_english }}
                                </div>
                            </div>              
                            <div class="col s12">
                                <h3>Imágenes</h3>
                            </div>
                            <div class="col s12 m6" v-for="(img,i) in modal.data.images" :key="'img-' + i">
                                <img :src="'img/blogs/' + img.file | asset " class="img-width"  alt="">
                            </div>
                        </template>
                        <template v-else>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿ Realmente deseas <b>Eliminar</b> ésta Colección ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal> -->
                </section>
            </div>
        </div>
         <byte-modal v-on:pressok="modal.action" :confirm="modal.type.confirm">

            <template v-if="modal.type.action == 'delete'">
                <div class="container-confirmation">
                    <div class="confimation__icon">
                        <i class="material-icons">error_outline</i>
                    </div>
                    <div class="confirmation__text">
                        <h5>¿ Realmente deseas <b>Eliminar</b> esta colección ?</h5>
                    </div>
                </div>
            </template>

            <template v-else-if="modal.type.action == 'postear'">
                <div class="container-confirmation">
                    <div class="confimation__icon">
                        <i class="material-icons">error_outline</i>
                    </div>
                    <div class="confirmation__text">
                        <h5>¿ Realmente deseas <b>{{ modal.data.status == 1 ? 'Desactivar ' : 'Publicar' }}</b> este Producto ?</h5>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="col s12">
                    <h3>Información del Producto</h3>
                </div>

                <div class="col s12 m6">
                    <span>Producto (Español):</span> {{ modal.data.name }}
                </div>
                <!--<div class="col s12 m6">
                    <span>Producto (Ingles):</span> {{ modal.data.name_english }}
                </div> -->

                <div class="col s12 m6">
                    <span>Descripción (Español):</span> {{ modal.data.description }}
                </div>

                <!--<div class="col s12 m6">
                    <span>Descripción (Ingles):</span> {{ modal.data.description_english }}
                </div>-->

                <div class="col s12 m6">
                    <span>Precio docena:</span> {{ modal.data.coin == "2" ? '$' : "Bs." }}{{ modal.data.price }}
                </div>


                <div class="col s12">
                    <h3>Imagenen Principal</h3>
                </div>

                <div class="col s12 m3" v-for="(img, i) in modal.data.images" :key="'img-main' + i" v-if="img.main == 1">
                    <img class="img-products responsive-img" :src="`img/products/${img.file}` | asset" alt="">
                </div>

                <div class="col s12">
                    <h3>Imagenenes Secundarias</h3>
                </div>

                <div class="col s12 m3" v-for="(img, i) in modal.data.images" :key="'img-main' + i" v-if="img.main == 0">
                    <img class="img-products responsive-img" :src="`img/products/${img.file}` | asset" alt="">
                </div>
            </template>

        </byte-modal>
    </article>
</template>

<script>
import { mapState } from 'vuex'

export default {
    template: "#template-wholesaler-index",
    data(){
        return {
            // setTable: [],
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: "view"
                },
                data: {},
                action: {},
            }
        }
    },
    computed: mapState({
        wholesalers: state => state.wholesalers.all
    }),
    methods: {
        setOption (option) {
            this.$store.dispatch('wholesalers/changeOption', option)
        },
        _view(item){
            this.modal.type.confirm = false;
            this.modal.type.action = "view";
            this.modal.data = item;
            this.modal.init.open();
        },

        selectItem(item) {
            this.$store.commit('wholesaler/setSelected', item.id)
            this.$store.dispatch('wholesalers/changeOption', 3)
        },

        _confirm(item, action) {
            this.modal.type.confirm = true;
            this.modal.type.action = this._delete;
            this.modal.data = item;

            if(action == "delete"){
                this.modal.type.action = "delete";
                this.modal.action = this._delete;
            }

            if(action == "postear"){
                this.modal.type.action = "postear";
                this.modal.action = this._postear;
            }

            this.modal.init.open();
        },
        _delete() {
            this.modal.init.close();
            this.$store.dispatch('wholesalers/deleteWholesaler', this.modal.data.id)
            // axios.delete(`admin/products/${this.modal.data.id}`)
            //     .then(res => {
            //         this.dataTable.splice(index, 1)
            //         this._showAlert(res.data.message, "success");
            //     })
            //     .catch(err => {
            //         this._showAlert('Disculpa, ha ocurrido un error', "error")
            //     });
        }
    },

    mounted() {
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
        this.$store.commit('wholesalers/setWholesalers', this.wholesaler)
    }
}
</script>


