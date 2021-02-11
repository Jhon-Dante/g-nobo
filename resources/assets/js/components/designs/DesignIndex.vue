<template id="template-design-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Diseños</h1>
                <h1 v-if="options == 1 || options == 2">{{ options == 1 ? 'Agregar' : 'Actualizar'}} Diseño</h1>
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
                                Agregar nuevo
                            </div>                            
                        </div>
                    </div>

                    <table-byte :set-table="dataTable" :filters="['name']">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Diseño (Español)</table-head>
                            <table-head>Diseño (Ingles)</table-head>
                            <table-head>Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>{{ item.name_english }}</table-cell>
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

                <design-form v-if="options == 1" @back="_resetView" @reload="_updateData"></design-form>
                <design-form v-if="options == 2" @back="_resetView" :action="1" :data="form" @reload="_updateData"></design-form>
            </div>
        </div>
        <byte-modal v-on:pressok="modal.type.action" :confirm="modal.type.confirm">
            <div class="container-confirmation">
                <div class="confimation__icon">
                    <i class="material-icons">error_outline</i>
                </div>
                <div class="confirmation__text">
                    <h5>¿ Realmente deseas <b>Eliminar</b> este Diseño ?</h5>
                </div>
            </div>
        </byte-modal>
    </section>
</template>

<style lang="scss">

</style>

<script>

import DesignForm from './DesignForm';

export default {
    template: "#template-design-index",
    components: {DesignForm},
    props: {
        designs: {
            type: Array,
            default() {
                return []
            }
        }
    },

    created () {
        this.dataTable = this.designs
    },

    data () {
        return {
            options: 0,
            dataTable: [],
            filters: [],
            form: {},
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
        _resetView (option) {
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
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modal.data.id
            })
            
            this.modal.init.close();

            axios.delete(`admin/designs/${this.modal.data.id}`)
                .then(res => {
                    this.dataTable.splice(index, 1)
                    this._showAlert(res.data.message, "success");
                })
                .catch(err => {
                    this._showAlert('Disculpa, ha ocurrido un error', "error")
                });
        },

        _updateData() {
            axios.get('admin/designs-all')
            .then(res => {
                this.dataTable = res.data
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
        this.filters = ['name', 'name_english']
        this.modal.init = M.Modal.init(document.querySelector('.modal'))
    }
}
</script>

