<template id="template-category-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Categorías</h1>
                <h1 v-if="options == 1 || options == 2">{{ options == 1 ? 'Registrar' : 'Actualizar' }} Categoría</h1>
            </div>

        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content" v-show="options == 0">
                    <div class="row">
                        <div class="col s12 container-btn-add">
                            <button class="btn-add" @click="options = 1">
                                 <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nueva
                            </div>                            
                        </div>
                    </div>

                    <table-byte :set-table="dataTable" :filters="['name']">

                        <table-row slot="table-head">
                            <table-head>Categoría (Español)</table-head>
                            <!--<table-head>Categoría (Ingles)</table-head>-->
                            <table-head>Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <!--<table-cell>{{ item.name_english }}</table-cell>-->
                            <table-cell>

                                <a href="#!" class="btn-action" @click="_view(item, 'view')" title="Ver">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_edit(item, 'edit')" title="Editar">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'destroy')" title="Eliminar">
                                    <img :src="'img/icons/ico-eliminar.png' | asset" alt="" class="img-responsive">
                                </a>

                            </table-cell>
                        </table-row>

                        <table-row slot="empty-rows">
                            <table-cell colspan="3">
                                No se encontraron registros.
                            </table-cell>
                        </table-row>

                    </table-byte>

                    <!-- Modal -->
                    <byte-modal v-on:pressok="_delete" :confirm="modal.type.confirm">
                        <template v-if="modal.type.action == 'destroy'">
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿Realmente desea <b>eliminar</b> la categoría <b>{{ modal.data.name }}</b> ?</h5>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <div class="col s12">
                                <h3>Categoría</h3>
                            </div>
                            <div class="col s12 m6">
                                <span>Categoría (Español):</span> {{ modal.data.name }}
                            </div>
                           <!-- <div class="col s12 m6">
                                <span>Categoría (Ingles):</span> {{ modal.data.name_english }}
                            </div> -->

                            <!-- <div class="col s12">
                                <h3>Tallas</h3>
                            </div>

                            <div class="col s12">
                                <div class="col s2" v-for="(size, i) in modal.data.sizes" :key="'sub-' + i">
                                    {{ size.name }}
                                </div>
                            </div> -->

                            <div class="col s12">
                                <h3>Subcategorías</h3>
                            </div>

                            <div class="col s12" v-for="(sub, i) in modal.data.subcategories" :key="'sub-' + i">
                                <div class="col s12 m6" >
                                    {{ sub.name }}
                                </div>
                                <!--<div class="col s12 m6">
                                    {{ sub.name_english }}
                                </div> -->
                            </div>
                        </template>
                        
                    </byte-modal>
                </section>

                <category-add v-if="options == 1" :sizes="sizes" :filters="catalogs" @back="_resetView"></category-add>
                <category-add v-if="options == 2" :sizes="sizes" :filters="catalogs" :set-form="edit" @back="_resetView"></category-add>

            </div>
        </div>
    </section>
</template>

<script>

import CategoryAdd from './CategoryAdd';

export default {
    template: "#template-category-index",
    components: {CategoryAdd},
    props: {
        url: {
            type: String,
            default: ""
        },

        categories: {
            type: Array,
            default () {
                return []
            }
        },

        sizes: {
            type: Array,
            default () {
                return []
            }
        },
        catalogs: {
            type: Array,
            default() {
                return []
            }
        }
    },

    created () {
        this.dataTable = this.categories;
    },

    data () {
        return {
            options: 0,
            dataTable: [],
            filters: [],
            filter: "",
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: 'view'
                },
                data: {}
            },
            edit: {},
        }
    },

    methods: {
        _resetView(option) {
            this.options = option;
        },

        _view(data, action){
            console.log(data);
            this.modal.type.action = action;
            this.modal.type.confirm = false;
            this.modal.data = data;
            this.modal.init.open();
        },

        _edit(item, action){
            this.edit = item;
            this.options = 2;
        },

        _confirm(data, action){
            console.log(data, action);
            this.modal.type.action = action;
            this.modal.type.confirm = true;
            this.modal.data = data;
            this.modal.init.open();

        },

        _delete() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modal.data.id
            })

            this.modal.init.close();

            axios.delete(`admin/categories/${this.modal.data.id}`)
                .then(res => {
                    if(res.data.result == undefined){
                        this.dataTable.splice(index, 1)
                        swal('', 'Se ha eliminado la categoría', "success");
                    }else{
                        swal('', res.data.error, "warning");
                    }
                })
                .catch(err => {
                    swal('', 'Disculpe, ha ocurrido un error', "error")
                });
        }
    },
    mounted(){
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
    }
}
</script>

