<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Tasa de Envio</h1>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content">
                    <table-byte :set-table="dataTable">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Monto</table-head>
                            <table-head>Tipo</table-head>
                            <table-head>Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.amount | money }}</table-cell>
                            <table-cell>{{ item.type == 1 ? 'Nacional' : 'Regional' }}</table-cell>
                            <table-cell class="head-actions">
                                <a href="#!" class="btn-action tooltipped" @click="_updateAmount(item)" data-position="top" data-delay="50" data-tooltip="Editar">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>
                            </table-cell>
                        </table-row>

                        <table-row slot="empty-rows">
                            <table-cell colspan="3">
                                No se encontraron registros.
                            </table-cell>
                        </table-row>
                    </table-byte>
                    <byte-modal id="update-shipping-fees-modal" v-on:pressok="modalUpdate.action" :confirm="modalUpdate.type.confirm">
                        <template>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5> Actualizando tasa de Envío</h5>
                                </div>
                            </div>
                            <div class="container-confirmation">
                                <div class="col s12 m12 l12 center-align">
                                    <label for="amount" class="label-impegno">Ingrese el monto</label>
                                    <input type="number" name="amount" id="amount" v-model="modalUpdate.form.amount" step="any" class="browser-default input-impegno">
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center-align">
                <h1>Mínimo de compra</h1>
            </div>
        </div>
        <card-main class="row">
            <card-content>
                <form @submit.prevent="_updateMinimum">
                    <div class="col s12 m4 l4 offset-l4 offset-m4 center-align">
                            <label for="amount" class="label-impegno">Ingrese el monto mínimo de compra $</label>
                            <input type="number" @keyup="_maxLength('value')" name="amount" id="amount" v-model="minimum.value" step="any" class="browser-default input-impegno">
                    </div>
                    <div class="col s12 m12 l12 margin-top center-align">
                        <button href="#!" class="btn btn-success" :disabled="!isValid">Guardar</button>
                        <br />
                        <br />
                    </div>
                </form>
            </card-content>
        </card-main>
    </section>
</template>

<style>
    .sweet-alert {
        z-index: 99999999;
    }
</style>


<script>
export default {
    name: 'shipping-fees-index',
    props: {
        shippingFees: {
            type: Array,
            default: []
        },
        minimunPurchase: {
            type: Object,
            default: {}
        }
    },
    data () {
        return {
            dataTable: [],
            modalUpdate: {
                init: {},
                data: {},
                form:{
                    amount: ''
                },
                action: {},
                type: {
                    confirm: false,
                    action: 'reject'
                }
            },
            minimum: {}
        }
    },
    computed: {
        isValid() {
            return !!this.minimum.value
        }
    },
    methods: {
        _maxLength (prop) {
            const value = this.minimum[prop]
            if (value.length > 10) {
                this.minimum[prop] = value.slice(0, -1)
                this._maxLength(prop)
            }
        },
        
        _refresh () {
            this.dataTable = this.shippingFees
            this.init = ''
            this.end = ''
        },

        _updateMinimum () {
            showLoading();
            axios.put(`admin/settings/${this.minimum.id}`, this.minimum)
                .then(res => swal("", "Se actualizaron los datos correctamente", "success"))
                .catch(err => { 
                    swal("", "Algo ha ocurrido intente nuevamente", "error")
                    console.log('err', err)
                })
                .finally(quiLoading);
        },

        _updateAmount(item) {
            this.modalUpdate.type.confirm = true;
            this.modalUpdate.type.action = this._updateAmountConfirm;
            this.modalUpdate.data = item;
            this.modalUpdate.action = this._updateAmountConfirm
            this.modalUpdate.form.amount = item.amount;
            this.modalUpdate.init.open();
        },

        _updateAmountConfirm() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalUpdate.data.id
            })
            var status = 2;
            this.modalUpdate.init.close();
            showLoading();
            axios.put(`admin/shipping-fees/${this.modalUpdate.data.id}`, {amount: this.modalUpdate.form.amount})
            .then(res => {
                this.dataTable[index].amount = res.data.shippingFee.amount;
                swal("", res.data.message, "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            })
            .finally(() => {
                quiLoading();
            });
        },
    },
    mounted() {
        this.minimum = { ...this.minimunPurchase }
        this.dataTable = this.shippingFees
        this.modalUpdate.init = M.Modal.init(document.querySelector('#update-shipping-fees-modal'));
    }
}
</script>
