<template id="template-allies-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Aliados</h1>
                <h1 v-if="options == 1 || options == 2">{{ options == 1 ? 'Registrar' : 'Actualizar' }} Aliado</h1>
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
                                Agregar nuevo
                            </div>
                        </div>
                    </div>

                    <table-byte :set-table="allies" :filters="['nombre', 'facebook', 'twitter', 'instagram']">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Nombre </table-head>
                            <table-head>Instagram</table-head>
                            <!-- <table-head>Facebook</table-head> -->
                            <!-- <table-head>Twitter </table-head> -->
                            <table-head class="th-action">Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>{{ item.instagram }}</table-cell>
                            <!-- <table-cell>{{ item.facebook }}</table-cell> -->
                            <!-- <table-cell>{{ item.twitter }}</table-cell> -->
                            <table-cell>

                                <a href="#!" class="btn-action" @click="_view(item)">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_edit(item, 'edit')">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'destroy')">
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
                    <byte-modal v-on:pressok="_delete" :confirm="modal.type.confirm">
                        <template v-if="modal.type.action == 'view'">
                            <div class="col s12">
                                <h3>Detalles del Aliado</h3>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Nombre</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.name }}
                                </div>
                                <div class="col s12">
                                    <h3>Facebook</h3>
                                </div>      
                                <div class="col s12">
                                    {{ modal.data.facebook }}
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Twitter</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.twitter }}
                                </div>
                                <div class="col s12">
                                    <h3>Instagram</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.instagram }}
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="col s12">
                                    <h3>Descripción</h3>
                                </div>
                                <div class="col s12">
                                   {{ modal.data.address}}
                                </div>
                            </div>              
                            <div class="col s12">
                                <h3>Imágenes</h3>
                            </div>
                            <div class="col s12 m6" v-for="(img,i) in modal.data.fotos" :key="'img-' + i">
                                <img :src="'img/aliados/' + img.file | asset " class="img-width"  alt="">
                            </div>
                        </template>
                        <template v-else>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿ Realmente deseas <b>Eliminar</b> el aliado ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                </section>

                <allies-add v-if="options == 1" @back="_resetView"></allies-add>
                <allies-update v-if="options == 2" :data="form" @back="_resetView"></allies-update>
            </div>
        </div>
        <!-- Modal -->
        <byte-modal v-on:pressok="_delete" :confirm="modal.type.confirm">
            <div class="container-confirmation">
                <div class="confimation__icon">
                    <i class="material-icons">error_outline</i>
                </div>
                <div class="confirmation__text">
                    <h5>¿Realmente deseas <b>eliminar</b> este aliado ?</h5>
                </div>
            </div>            
        </byte-modal>

    </section>
</template>

<style lang="scss">
    textarea{
        resize: none; 
        height: 10rem !important;
    }
    .items__file{
        position: relative;
    }

    .img-width{
        width:100%;
        height: 300px;
        object-fit: contain;
    }
    .th-action{
        width: 200px;
    }
</style>


<script>
import AlliesAdd from './AlliesAdd'
import AlliesUpdate from './AlliesUpdate'

export default {
    template: "#template-allies-index",

    components: {AlliesAdd, AlliesUpdate},

    props: {
        allies: {
            type: Array,
            default () {
                return []
            }
        }
    },

    data () {
        return {
            options: 0,
            form: {},
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: 'view'
                },
                data: {}
            },
            setTable: []
        }
    },

    methods: {
        _resetView(e) {
            this.options = e
        },

        _edit (item, name = null) {
            this.form = item
            this.options = 2
        },
        _view(item){
            this.modal.type.confirm = false;
            this.modal.type.action = "view";
            this.modal.data = item;
            this.modal.init.open();
        },
        _confirm(item) {
            this.modal.type.confirm = true;
            this.modal.type.action = this._delete;
            this.modal.data = item;
            this.modal.init.open();
        },

        _delete () {
            let index = this.allies.findIndex(e => {
                return e.id == this.modal.data.id
            })
            
            this.modal.init.close();

            axios.delete(`admin/allies/${this.modal.data.id}`)
                .then(res => {
                    this.allies.splice(index, 1)
                    this._showAlert(res.data.message, "success");
                })
                .catch(err => {
                    this._showAlert('Disculpa, ha ocurrido un error', "error")
                });
        },

        _showAlert(text, type) {
            swal({
                title: "",
                text: text,
                timer: 3000,
                showConfirmButton: false,
                type: type
            })
        }

    },

    mounted() {
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
        this.setTable = this.allies; 
    }
}
</script>


