<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Clientes</h1>
                <h1 v-if="options == 1 || options == 2">
                    {{ options == 1 ? 'Agregar' : 'Actualizar'}} Cliente {{form.name}}
                </h1>
            </div>

            <div class="row">
                <div class="col s12">
                    <section class="table__content" v-if="options == 0">
                        <table-byte 
                            :forPage="25"
                            :set-table="dataTable" 
                            :filters="['name']"
                            :export-file="true"
                            :export-url="'admin/clients/export'"
                            :export-title="'Reporte-Clientes'"
                        >
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Nombre</table-head>
                            <table-head>Identificación</table-head>
                            <table-head>Tipo</table-head>
                            <table-head>Fecha de registro</table-head>
                            <table-head>Teléfono</table-head>
                            <table-head>Estado del Cliente</table-head>
                            <table-head style="min-width: 250px;">Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.name }}</table-cell>
                            <table-cell>{{ item.identificacion }}</table-cell>
                            <table-cell>
                                <template v-if="item.persona == '1'">Natural</template>
                                <template v-else>Jurídico</template>    
                            </table-cell>
                            <table-cell>{{ item.created_at | date }}</table-cell>
                            <table-cell>{{ item.telefono }} </table-cell>
                            <table-cell>
                                {{item.status == 2 ? 'Eliminado' : item.status == 1 ? 'Activo' : 'Desactivado'}} 
                            </table-cell>
                            <table-cell>
                                <a v-if="item.status != 2" href="#!" class="btn-action" @click="_confirm(item)" :title="`${item.status == '1' ? 'Desactivar': 'Activar'}`">
                                    <img :src="'img/icons/ico-toggle-on.svg' | asset" alt="" class="img-responsive" style="width: 36px; margin: 0;" v-if="item.status == 1">
                                    <img :src="'img/icons/ico-toggle-off.svg' | asset" alt="" class="img-responsive" style="width: 36px; margin: 0;" v-if="item.status == 0">
                                </a>
                                <a href="#!" class="btn-action" @click="_view(item)" title="Ver">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>
                                <a v-if="item.status != 2" href="#!" class="btn-action" @click="_edit(item)" title="Editar">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt class="img-responsive" />
                                </a>
                                <a href="#!" class="btn-action" @click="_purchase(item)" title="Pedidos">
                                    <img :src="'img/icons/ico-purchase.png' | asset" alt="" class="img-responsive">
                                </a>
                                <a v-if="item.status != 2" href="#!" class="btn-action" @click="_confirmDelete(item)" title="Eliminar">
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
                    <byte-modal>
                        <template>
                            <div class="col s12">
                                <h3>Datos del cliente</h3>
                            </div>
                            <div class="col s12 m6"><b>Nombre:</b> {{ modal.data.name }}</div>
                            <div class="col s12 m6"><b>Identificación:</b> {{ modal.data.identificacion }}</div>
                            <div class="col s12 m6"><b>Telefono:</b> {{ modal.data.telefono }}</div>
                            <div class="col s12 m6"><b>Correo electrónico:</b> {{ modal.data.email }}</div>
                            <div class="col s12 m6">
                                <b>Estado del cliente:</b> 
                                {{modal.data.status == 2 ? 'Eliminado' : modal.data.status == 1 ? 'Activo' : 'Desactivado'}} 
                            </div>
                            <div class="col s12 m6" v-if="modal.data.type == 2"><b>Empresa:</b> {{ modal.data.empresa }}</div>
                            <div class="col s12 m6"><b>Codigo postal:</b> {{ modal.data.codigo }}</div>
                            <div class="col s12 m12"><b>Dirección:</b> {{ modal.data.direccion }}</div>
                            <div class="col s12 m6" v-if="modal.data.pais"><b>Pais:</b> {{ modal.data.pais.nombre }}</div>
                            <div class="col s12 m6"  v-if="modal.data.estado"><b>Estado:</b> {{ modal.data.estado.nombre }}</div>
                            <div class="col s12 m6"  v-if="modal.data.municipality"><b>Municipio:</b> {{ modal.data.municipality.nombre }}</div>
                            <div class="col s12 m6"  v-if="modal.data.parish"><b>Sector:</b> {{ modal.data.parish.nombre }}</div>
                        </template>
                    </byte-modal>
                    <byte-modal id="purchase">
                        <template>
                            <div class="col s12">
                                <h3>Datos de los pedidos</h3>
                            </div>
                            <div class="col s12 underline" v-for="(pedido, i) in modalPurchase.data.pedidos" :key="'pedido-'+i">
                                <div class="col s12 m3">
                                    <div class="col s12 m12"><b>N° de Pedido: {{ padZeros(pedido.id) }}</b></div>
                                </div>
                                <div class="col s12 m3">
                                    <div class="col s12 m12"><h6>Fecha: {{ pedido.created_at }}</h6></div>
                                </div>
                                <div class="col s12 m3">
                                    <div class="col s12 m12">
                                        <h6>
                                            Tipo de pago: 
                                            {{ pay_types[pedido.payment_type] }}
                                        </h6>
                                    </div>
                                </div>
                                <!-- <div class="col s12 m3" v-if="pedido.currency == 1"><b>Total:</b>{{ getTotal(pedido) | VES }}</div> -->
                                <div class="col s12 m3" v-if="pedido.currency == 1"><b>Total:</b>{{ pedido.total | VES }}</div>
                                <!-- <div class="col s12 m3" v-if="pedido.currency == 2"><b>Total:</b>{{ getTotal(pedido) | USD }}</div> -->
                                <div class="col s12 m3" v-if="pedido.currency == 2"><b>Total:</b>{{ pedido.total | USD }}</div>
                            </div>
                        </template>
                    </byte-modal>
                    <byte-modal id="switch" v-on:pressok="modalSwitch.action" :confirm="modalSwitch.type.confirm">
                        <template>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿ Realmente deseas <b>{{ modalSwitch.data.status == 1 ? 'Desactivar ' : 'Activar' }}</b> el Usuario {{ modalSwitch.data.name }} ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                    <byte-modal id="delete-client-modal" v-on:pressok="modalDelete.action" :confirm="modalDelete.type.confirm">
                        <template>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿ Realmente deseas <b>Eliminar</b> el Usuario {{ modalDelete.data.name }} ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
            
                    </section>
                    <client-edit
                        v-if="options == 2"
                        @back="_resetView"
                        :data="form"
                        :states="states"
                    >
                    </client-edit>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
    .underline {
        border-bottom: 3px solid #000000;
        margin-bottom: 10px;
    }
</style>


<script>
export default {
    name: '',
    props: {
        clients: {
            type: Array,
            default: []
        },
        states: {
            type: Array,
            default: []
        }
    },
    data () {
        return {
            options: 0,
            form: {},
            dataTable: [],
            pay_types: ['', 'Transferencia', 'Pago Movil', 'Zelle', 'Paypal', 'Efectivo', 'Stripe'],
            modal: {
                init: '',
                data: {},
                type: {
                    action: 'view'
                }
            },
            modalPurchase: {
                init: '',
                data: {},
                type: {
                    action: 'purchase'
                }
            },
            modalSwitch: {
                init: {},
                data: {},
                action: {},
                type: {
                    confirm: false,
                    action: 'switch'
                }
            },
            modalDelete: {
                init: {},
                data: {},
                action: {},
                type: {
                    confirm: false,
                    action: 'delete'
                }
            },
            total: ''
        }
    },
    methods: {
        _view(item){
            this.modal.data = item;
            this.modal.init.open();
        },
        _purchase(item) {
            this.dataTable.forEach(element => {
                
            });
            this.modalPurchase.data = item;
            this.modalPurchase.init.open();
        },
        _confirm(item) {
            this.modalSwitch.type.confirm = true;
            this.modalSwitch.type.action = this._switch;
            this.modalSwitch.data = item;
            this.modalSwitch.action = this._switch
            this.modalSwitch.init.open();
        },
        _switch() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalSwitch.data.id
            })
            var status = this.modalSwitch.data.status == 1 ? 0 : 1;
            this.modalSwitch.init.close();
            axios.post(`admin/clients/switch/${this.modalSwitch.data.id}`, {status:status})
            .then(res => {
                this.dataTable[index].status = !this.dataTable[index].status
                swal("", 'Se cambio el estatus correctamente', "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            });

        },
        getTotal (item) {
            let total = 0
                
            let subtotal = 0; 
            let price = 0;
            item.details.forEach(e => {
                if(item.payment_type == 5) {
                    price = e.price
                } else {
                    if(item.payment_type == 3 || item.payment_type == 4) { // Si es PAYPAL
    
                        if(e.coin == 1) {
                            price = parseFloat(e.price / item.exchange.change).toFixed(2)
                        }else {
                            price = e.price
                        }
                        
                    }else {
    
                        if(e.coin == 1) {
                            price = e.price
                        }else {
                            price = parseFloat(e.price * item.exchange.change).toFixed(2)
                        }
    
                    }
                }
                subtotal = price * e.quantity
                
                total += subtotal
            })

            return total.toFixed(2)
        },
        /*getTotal (item) {
            let total = 0
            item.details.forEach(e => {
                total += e.price;
            })
            return total
        },*/
        getPrice(precio,coin,exchange, pay) {
            var price = precio;
            if (coin == '1' && pay == '2') {
                price = parseFloat(price / exchange).toFixed(2);
            }
            else if (coin == '2' && pay == '1') {
                price = parseFloat(price * exchange).toFixed(2);
            }
            return price;
        },
        findCurrency (item) {
            let currency = 1
            item.details.forEach(e => {
                currency = e.coin
            })

            return currency == 1 ? 'Bs.' : 'USD'
        },
        _confirmDelete(item) {
            this.modalDelete.type.confirm = true;
            this.modalDelete.type.action = this._delete;
            this.modalDelete.data = item;
            this.modalDelete.action = this._delete
            this.modalDelete.init.open();
        },
        _delete() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalDelete.data.id
            })
            var status = 2;
            this.modalDelete.init.close();
            axios.post(`admin/clients/delete/${this.modalDelete.data.id}`, {status:status})
            .then(res => {
                this.dataTable[index].status = status;
                swal("", 'Se elimino el cliente de manera exitosa', "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            });
        },
        _resetView(option) {
            this.options = option;
            this.form = {};
        },
        _updateData() {
            axios.get("admin/clients/all")
            .then(res => {
                this.dataTable = [
                    ...res.data.map(d => ({ ...d}))
                ];
            })
            .catch(err => {
                console.log(err);
            });
        },
        _edit(item) {
            this.options = 2;
            this.form = item;
        },
        padZeros(num) {
            return num.toString().padStart(5, "0");
        }
    },
    mounted () {
        this.dataTable = this.clients;
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
        this.modalPurchase.init = M.Modal.init(document.querySelector('#purchase'));
        this.modalSwitch.init = M.Modal.init(document.querySelector('#switch'));
        this.modalDelete.init = M.Modal.init(document.querySelector('#delete-client-modal'));
    }
}
</script>
