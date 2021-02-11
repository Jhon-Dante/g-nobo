<template id="template-product-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Cuentas bancarias</h1>
            </div>
        </div>
        <div class="row" v-if="options == 0">
            <div class="col s12">
                <section class="table__content" >
                    <div class="row">
                        <a class="col s12 container-btn-add"  @click="options = 1">
                            <button class="btn-add">
                                 <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nuevo
                            </div>                            
                        </a>
                    </div>

                    <table-byte :set-table="dataTable" :filters="['name']">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Cuenta</table-head>
                            <table-head>Banco</table-head>
                            <table-head>Nro. cta.</table-head>
                            <table-head>Identificación</table-head>
                            <table-head>Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>{{ item.bank.name }}</table-cell>
                            <table-cell>{{ item.number }}</table-cell>
                            <table-cell>{{ item.identification }}</table-cell>
                            <table-cell>
                                <!-- <a href="#!" class="btn-action" @click="_view(item)">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a> -->

                                <a href="#!" class="btn-action" @click="_edit(item)" title="Editar">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'postear')" :title="`${item.status == '1' ? 'Desactivar': 'Activar'}`" >
                                    <img :src="'img/icons/ico-toggle-on.svg' | asset" alt="" class="img-responsive" style="width: 36px; margin: 0;" v-if="item.status == '1'">
                                    <img :src="'img/icons/ico-toggle-off.svg' | asset" alt="" class="img-responsive" style="width: 36px; margin: 0;" v-if="item.status == '0'">
                                </a>

                                <a href="#!" class="btn-action" @click="_confirm(item, 'delete')" title="Eliminar">
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
            </div>
        </div>
        <byte-modal v-on:pressok="modal.action" :confirm="modal.type.confirm">

            <template v-if="modal.type.action == 'delete'">
                <div class="container-confirmation">
                    <div class="confimation__icon">
                        <i class="material-icons">error_outline</i>
                    </div>
                    <div class="confirmation__text">
                        <h5>¿ Realmente deseas <b>Eliminar</b> este cuenta bancaria ?</h5>
                    </div>
                </div>
            </template>

            <template v-else-if="modal.type.action == 'postear'">
                <div class="container-confirmation">
                    <div class="confimation__icon">
                        <i class="material-icons">error_outline</i>
                    </div>
                    <div class="confirmation__text">
                        <h5>¿ Realmente deseas <b>{{ modal.data.status == 1 ? 'Desactivar ' : 'Activar' }}</b> esta cuenta bancaria ?</h5>
                    </div>
                </div>
            </template>
        </byte-modal>

        <bank-form v-if="options == 1" @back="options = 0" :accounts="accounts" :banks="banks"></bank-form>
        <bank-edit v-if="options == 2" @back="options = 0" :accounts="accounts" :banks="banks" :setform="setform"></bank-edit>
    </section>
</template>

<style lang="scss">
    .img-products{
        height: 80%;
        width: 80%;
        object-fit: contain
    }
</style>

<script>
import BankForm from './BankForm'
import BankEdit from './BankEdit'

export default {
    template: "#template-product-index",
    props: {
        banks: {
            type: Array,
            default: []
        },
        accounts: {
            type: Array,
            default: []
        }
    },
    components: {
        BankForm,
        BankEdit
    },
    created (){
        this.dataTable = this.accounts
    },

    data () {
        return {
            options: 0,
            form: {},
            dataTable: [],
            setform: {},
            filters: [],
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: 'view'
                },
                action: {},
                data: {
                    collections: {},
                    categories: {},
                    subcategories: {},
                    designs: {}
                }
            }
        }
    },

    methods: {

        _resetView (option) {
            this.options = option
        },

        _view(item){
            console.log(item);
            this.modal.type.confirm = false;
            this.modal.type.action = "view";
            this.modal.data = item;
            this.modal.init.open();
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

        _edit(item) {
            this.options = 2
            this.setform = item
        },

        _delete(){
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modal.data.id
            })
            
            this.modal.init.close();

            axios.delete(`admin/banks/${this.modal.data.id}`)
                .then(res => {
                    this.dataTable.splice(index, 1)
                    this._showAlert('Se elimino la cuenta correctamente', "success");
                })
                .catch(err => {
                    this._showAlert('Disculpa, ha ocurrido un error', "error")
                });
        },

        _postear(){
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modal.data.id
            })
            
            this.modal.init.close();

            axios.post(`admin/banks/switch/${this.modal.data.id}`)
                .then(res => {
                    console.log(this.dataTable[index]);
                    this.dataTable[index].status = !this.dataTable[index].status
                    swal("", 'Se cambio el estatus correctamente', "success");
                })
                .catch(err => {
                    this._showAlert('Disculpa, ha ocurrido un error', "error")
                });
        },

        _updateData() {
            axios.get('admin/products-all')
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
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
    }
}
</script>
