<template id="template-size-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Tallas</h1>
                <h1 v-if="options == 1 || options == 2">{{ options == 1 ? 'Agregar' : 'Actualizar'}} Talla</h1>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content" v-if="options == 0">
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
                    <table-byte :set-table="sizesArray" :filters="['name']">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Nombre de la talla</table-head>
                            <table-head>Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>

                                <a href="#!" class="btn-action" @click="_edit(item, 'edit')">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'destroy')">
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
                </section>

                <size-form v-if="options == 1" @back="_resetView" @reload="_updateData"></size-form>
                <size-form v-if="options == 2" @back="_resetView" :action="1" :data="form" @reload="_updateData"></size-form>
            </div>
        </div>
        <byte-modal v-on:pressok="modal.type.action" :confirm="modal.type.confirm">
            <div class="container-confirmation">
                <div class="confimation__icon">
                    <i class="material-icons">error_outline</i>
                </div>
                <div class="confirmation__text">
                    <h5>¿ Realmente deseas <b>Eliminar</b> esta talla ?</h5>
                </div>
            </div>
        </byte-modal>
    </section>
</template>

<style lang="scss">

</style>

<script>

import SizeForm from "./SizeForm";

export default {
    template: "#template-size-index",
    components: { SizeForm },
    props: {
        url: {
            type: String,
            default () {
                return ""
            }
        },

        sizes: {
            type: Array,
            default () {
                return []
            }
        }
    },

    created () {
        this.sizesArray = this.sizes
    },

    data () {
        return {
            options: 0,
            sizesArray: [],
            filters: [],
            filter: "",
            form: {
                id: 0,
                name: ""
            },
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: 'view'
                },
                data: {
                    name: ''
                }
            }
        }
    },

    methods: {
        _resetView(option) {
            this.options = option
        },

        _edit(item) {
            this.options = 2
            this.form = item
        },

        _confirm(item) {
            this.modal.type.confirm = true;
            this.modal.type.action = this._delete;
            this.modal.data = item;
            this.modal.init.open();
        },

        _delete(){
            let index = this.sizesArray.findIndex(e => {
                return e.id == this.modal.data.id
            })
            
            this.modal.init.close();

            axios.delete(`admin/sizes/${this.modal.data.id}`)
                .then(res => {
                    if(res.data.result){
                        this.sizesArray.splice(index, 1)
                        this._showAlert(res.data.message, "success");
                        return
                    }

                    this._showAlert(res.data.error, "error");
                })
                .catch(err => {
                    this._showAlert('Disculpa, ha ocurrido un error', "error")
                });
        },

        _updateData() {
            axios.get('admin/sizes-all')
            .then(res => {
                this.sizesArray = res.data
            })
            .catch(err => {
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
            })
        }
    },

    mounted () {
        this.filters = ['name']
        this.modal.init = M.Modal.init(document.querySelector('.modal'))
    }
}
</script>

