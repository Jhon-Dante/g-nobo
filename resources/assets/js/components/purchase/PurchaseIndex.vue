<template>
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Pedidos</h1>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content">
                    <div class="row">

                        <div class="col s12 m3">
                            <input type="text" class="datepicker browser-default input-impegno" placeholder="Fecha inicio" id="date_picker_init" v-model="init">
                        </div>

                        <div class="col s12 m3">
                            <input type="text" class="datepicker browser-default input-impegno" placeholder="Fecha final" id="date_picker_end" v-model="end">
                        </div>
                        
                        <div class="col s12 m2">
                            <select name="" id="" v-model="status" class="browser-default">
                                <option value="">Todos</option>
                                <option value="0">En espera</option>
                                <option value="1">Procesando</option>
                                <option value="3">Completado</option>
                                <option value="2">Rechazado</option>
                            </select>
                        </div>

                        <div class="col s1 m1" style="cursor: pointer; margin-top: 10px;">
                            <i class="material-icons" @click="_refresh">refresh</i>
                        </div>

                        <div class="col s12 m3">
                            <button type="button" class="btn btn-success" style="margin-top: 5px;" @click="_search">Consultar</button>
                        </div>
                    </div>

                    <new-table-byte
                        :setSearchValue="value => this.search = value"
                        :searchValue="this.search"
                        :setPageValue="page => _setPage(page)"
                        :pageValue="this.page"
                        :pages="this.pages"
                        :filtersData="{ 
                            init: this.init, 
                            end: this.end, 
                            status: parseInt(this.status),
                            search: this.search || ''
                        }"
                        :set-table="dataTable"
                        :load="params => _load(params)"
                        :filters="['user.name', 'code']" 
                        :export-file="true"
                        :export-url="'admin/purchases/export'"
                        :export-title="'Reporte-Pedidos'"
                    >
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>ID</table-head>
                            <!-- <table-head>Referencia de la transacción</table-head> -->
                            <table-head>Fecha - Hora</table-head>
                            <table-head>Cliente</table-head>
                            <table-head>Monto</table-head>
                            <table-head>Medio de pago</table-head>
                            <table-head>Tipo de Entrega</table-head>
                            <table-head>Estado</table-head>
                            <table-head style="min-width: 110px;">Acciones</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>{{ item.id }}</table-cell>
                            <!-- <table-cell>{{ item.code }}</table-cell> -->
                            <table-cell>{{ item.created_at | dateTime }}</table-cell>
                            <table-cell>{{ item.user.name }}</table-cell>
                            <table-cell v-if="item.currency == 1">{{ getTotal(item) | VES }}</table-cell>
                            <table-cell v-if="item.currency == 2">{{ getTotal(item) | USD }}</table-cell>
                            <table-cell>{{ pay_types[item.payment_type] }}</table-cell>
                            <table-cell>{{ item.deliveryType }}</table-cell>
                            <table-cell>{{ item.statusType }}</table-cell>
                            <table-cell class="head-actions">
                                <a href="#!" class="btn-action tooltipped" @click="_view(item)" data-position="top" data-delay="50" data-tooltip="Ver">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>
                                <a v-if="item.status == 0" href="#!" class="btn-action tooltipped" @click="_approve(item)" data-position="top" data-delay="50" data-tooltip="Aprobar">
                                    <i class="material-icons action-icon-success">check_box</i>
                                </a>
                                <a v-if="item.status == 1" href="#!" class="btn-action tooltipped" @click="_completed(item)" data-position="top" data-delay="50" data-tooltip="Completar">
                                    <i class="material-icons action-icon-success">check_box</i>
                                </a>
                                <a href="#!" class="btn-action tooltipped" @click="_prinpdf(item)" data-position="top" data-delay="50" data-tooltip="Imprimir">
                                    <img :src="'img/icons/printer-ico.png' | asset" alt="" class="img-responsive">
                                </a>
                                <a v-if="item.status == 0" href="#!" class="btn-action tooltipped" @click="_reject(item)" data-position="top" data-delay="50" data-tooltip="Rechazar">
                                    <img :src="'img/icons/ico-eliminar.png' | asset" alt="" class="img-responsive">
                                </a>
                            </table-cell>
                        </table-row>

                        <table-row slot="empty-rows">
                            <table-cell colspan="7">
                                No se encontraron registros.
                            </table-cell>
                        </table-row>

                    </new-table-byte>

                    <byte-modal>
                        <template>
                            <div class="col s12">
                                <h3>Detalle del pedido</h3>
                            </div>
                            
                            <div class="col s12 m6" v-if="modal.data.user"><b>Cliente:</b> {{ modal.data.user.name }}</div>
                            <div class="col s12 m6"><b>Fecha - Hora:</b> {{ modal.data.created_at | dateTime }}</div>
                            <div class="col s12 m6"><b>Metodo de pago:</b> {{ pay_types[modal.data.payment_type] }}</div>
                            <div class="col s12 m6" v-if="modal.data.payment_type != '5'"><b>ID/Referencia de la transacción:</b> #{{ modal.data.code }}</div>
                            <div class="col s12 m6">
                                <b>Tipo de Entrega:</b> {{ modal.data.deliveryType }}
                            </div>
                            <div v-if="modal.data.delivery">
                                <div v-if="modal.data.delivery && modal.data.delivery.address" class="col s12 m6">
                                    <b>Dirección de referencia :</b> 
                                    {{ modal.data.delivery ? modal.data.delivery.address : '' }}
                                </div>
                                <div class="col s12 m6" v-if="modal.data.delivery && modal.data.delivery.pay_with">
                                    <b>Monto de pago del cliente:</b>
                                    {{ modal.data.delivery.pay_with }} {{ modal.data.currency == 2 ? 'USD' : 'Bs.' }}
                                </div>
                                <div class="col s12 m6"><b>Estado :</b> {{ modal.data.delivery ? modal.data.delivery.state.nombre : '' }}</div>
                                <div class="col s12 m6" v-if="modal.data.delivery.municipality && modal.data.delivery.municipality.name"><b>Municipio :</b> {{ modal.data.delivery ? modal.data.delivery.municipality.name : '' }}</div>
                                <div class="col s12 m6" v-if="modal.data.delivery && modal.data.delivery.parish && modal.data.delivery.parish.name"><b>Sector :</b> {{ modal.data.delivery ? modal.data.delivery.parish.name : '' }}</div>
                                <div class="col s12 m6" v-if="modal.data.delivery && modal.data.delivery.turn"><b>Turno :</b> {{ modal.data.delivery ? turns[modal.data.delivery.turn] : '' }}</div>
                                <div class="col s12 m12" v-if="modal.data.delivery && modal.data.delivery.note"><b>Nota :</b> {{ modal.data.delivery ? modal.data.delivery.note : '' }}</div>
                            </div>
                            <div class="col s12 m6" v-if="modal.data.transfer && modal.data.transfer.bank_account && modal.data.transfer.bank_account.bank && modal.data.transfer.bank_account.bank.name">
                                <b>Banco :</b> 
                                {{ modal.data.transfer ? modal.data.transfer.bank_account.bank.name : '' }}
                            </div>
                            <div class="col s12 m6" v-if="modal.data.transfer && modal.data.transfer.bank_account">
                                <b>Cuenta :</b> 
                                {{ modal.data.transfer ? modal.data.transfer.bank_account.number : '' }}
                            </div>
                            <div class="col s12 m6" v-if="modal.data.transfer && modal.data.payment_type == 4">
                                <b>Zelle :</b> 
                                {{ modal.data.transfer ? modal.data.transfer.name : '' }}
                            </div>
                            <div class="col s12 m6" v-if="modal.data.delivery"><b>Fecha de Entrega:</b> {{ modal.data.delivery.date | date }}</div>
                            <div class="col s12">
                                <table>
                                    <thead>
                                        <th>Descripción</th>
                                        <th>Presentación</th>
                                        <th>Impuesto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody v-if="modal.data.details">
                                        <tr v-for="(d, i) in modal.data.details" :key="i">
                                            <td>
                                                <template v-if="d.producto && d.producto.name"> 
                                                    {{ d.producto.name }} 
                                                    {{ d.presentation || 'Sin presentacion' }} 
                                                    {{ d.unit }}
                                                    {{ d.discounts_text }} 
                                                </template>    
                                                <template v-else>{{ d.discount_description }}</template>
                                            </td>
                                            <td>
                                                <p v-if="d.product_amount">
                                                    {{d.product_amount.presentation}} {{getUnit(d.product_amount.unit)}}
                                                </p>
                                                <p v-else>
                                                    <strong>Descuento</strong>
                                                </p>
                                            </td>
                                            <td>
                                                <span v-if="d.producto">{{ d.producto.taxe ? d.producto.taxe.name : 'Exento' }}</span>
                                            </td>
                                            <td v-if="modal.data.currency == 1">{{ getPrice(d.price, d.coin, modal.data.exchange.change, modal.data.currency) | VES }}</td>
                                            <td v-if="modal.data.currency == 2">{{ getPrice(d.price, d.coin, modal.data.exchange.change, modal.data.currency) | USD }}</td>
                                            <td>{{ d.quantity }}</td>
                                            <td v-if="modal.data.currency == 1">{{ getPrice(d.price, d.coin, modal.data.exchange.change, modal.data.currency) * d.quantity | VES }} </td>
                                            <td v-if="modal.data.currency == 2">{{ getPrice(d.price, d.coin, modal.data.exchange.change, modal.data.currency) * d.quantity | USD }} </td>
                                           
                                        </tr>
                                        <tr v-if="modal.data.payment_type == 4">
                                            <td colspan="5" class="right-align"><b>Comisión PayPal</b></td>
                                            <!-- <td v-if="modal.data.currency == 1">{{ comission(modal.data) | VES }}</td> -->
                                            <td v-if="modal.data.currency == 2">{{ comission(modal.data) | USD }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="right-align"><b>Subtotal</b></td>
                                            <td v-if="modal.data.currency == 1">{{ getSubtotal(modal.data) | VES }}</td>
                                            <td v-if="modal.data.currency == 2">{{ getSubtotal(modal.data) | USD }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="right-align"><b>Costo de envío</b></td>
                                            <td v-if="modal.data.currency == 1">{{ getShippingFee(modal.data) | VES }}</td>
                                            <td v-if="modal.data.currency == 2">{{ getShippingFee(modal.data) | USD }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="right-align"><b>Total</b></td>
                                            <td v-if="modal.data.currency == 1">{{ getTotal(modal.data) | VES }}</td>
                                            <td v-if="modal.data.currency == 2">{{ getTotal(modal.data) | USD }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>

                    </byte-modal>
                    <byte-modal id="approve-purchase-modal" v-on:pressok="modalApprove.action" :confirm="modalApprove.type.confirm">
                        <template>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text" v-if="modalApprove.data.id">
                                    <h5>¿ Realmente deseas <b>{{ statusName }}</b> el Pedido {{ padZeros(modalApprove.data.id) }} ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                    <byte-modal id="reject-purchase-modal" v-on:pressok="modalReject.action" :confirm="modalReject.type.confirm">
                        <template>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text" v-if="modalReject.data.id">
                                    <h5>¿ Realmente deseas <b>Rechazar</b> el Pedido {{ padZeros(modalReject.data.id) }} ?</h5>
                                </div>
                            </div>
                            <div class="container-confirmation">
                                <div class="col s12 m12 l12 center-align">
                                    <label for="rejectReason" class="label-impegno">Ingrese el motivo de la cancelacion del pedido</label>
                                    <textarea 
                                        name="rejectReason" 
                                        id="rejectReason" 
                                        v-model="modalReject.data.rejectReason" 
                                        class="browser-default textarea-impegno">
                                    </textarea>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                </section>
            </div>
        </div>
    </section>
</template>

<style lang="scss">
    .sweet-alert {
        z-index: 99999999;
    }
    .action-icon {
        vertical-align: middle;
        &-success {
            color: #20671c !important;
            vertical-align: middle;
        }
    }
</style>


<script>
import { unities } from "../../constants";
export default {
    name: 'purchase-index',
    props: {},
    data () {
        return {
            dataTable: [],
            fullPurchases: [],
            init: '',
            end: '',
            status: '',
            search: '',
            submitted: false,
            pay_types: ['', 'Transferencia', 'Pago Movil', 'Zelle', 'Paypal', 'Efectivo', 'Stripe'],
            turns: ['', 'Mañana', 'Tarde', 'Noche'],
            pages: 0,
            page: 1,
            modal: {
                init: '',
                initErr: false,
                endErr: false,
                data: {},
                type: {
                    action: 'view'
                }
            },
            modalApprove: {
                init: {},
                data: {},
                action: {},
                type: {
                    confirm: false,
                    action: 'approve'
                }
            },
            modalReject: {
                init: {},
                data: {},
                action: {},
                type: {
                    confirm: false,
                    action: 'reject'
                }
            },
            statusName: 'Aprobar'
        }
    },
    watch:{
        'page': function(newVal, oldVal){
            if(newVal != oldVal){
                this._search()
            }
        }
    },
    methods: {
        getUnit(unit) {
            let val = unities.find(u => u.id == unit)
            return val && val.name || '';
        },
        _setPage(page){
            this.page = page
            this._search()
        },
        _prinpdf (item) {
            const options = {
                responseType: "blob",
            };
            
            axios.post(`admin/pedidos-pdf/${item.id}`, {}, options)
                .then(res => {
                    const url = window.URL.createObjectURL(
                        new Blob([res.data], { type: "application/pdf" })
                    );
                    const link = document.createElement("a");
                    const ext = ".pdf";
                    const filename =
                        'pedido' + "_" + moment().format("DD-MM-YYYY") + ext;
                    link.href = url;
                    link.setAttribute("download", `${filename}`);
                    document.body.appendChild(link);
                    link.click(); 
                })
                .catch(err => console.error(err))
        },

        _refresh () {
            this.init = ''
            this.end = ''
            this.status = ''
            this.$emit('deleteFilter')
        },
        async _getDetails(id){
            showLoading()
            let details = []
            const {data} = await axios.post(`admin/purchases/getDetails`, {id})
            if(data){
                details = data.details
            }
            quiLoading()
            return details
        },
        async _view(item){
            let rawItem = {... item}
                rawItem.details = await this._getDetails(item.id)
            this.modal.data = rawItem;
            this.modal.init.open();
        },

        findCurrency (item) {
            let currency = 1
            item.details.forEach(e => {
                currency = e.coin
            })

            return currency == 1 ? 'Bs.' : 'USD'
        },

       
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

        getTotal(pedido) {
            if(pedido.payment_type == 4){
                var comission = this.comission(pedido);
            }else{
                var comission = 0;
            }
            // if (pedido.payment_type == 4) {}
            return (parseFloat(this.getShippingFee(pedido)) + parseFloat(this.getSubtotal(pedido)) + comission).toFixed(2)
        },

        getTotal2(pedido) {
            return (parseFloat(this.getShippingFee(pedido)) + parseFloat(this.getSubtotal(pedido))).toFixed(2)
        },

        getShippingFee(pedido) {
            return parseFloat(this.getPrice(pedido.shipping_fee, 2, pedido.exchange.change, pedido.currency)).toFixed(2)
        },

        getSubtotal (item) {
            let total = item.subtotal
            if(item.currency != 2) { // Si no es Dolar
                total = parseFloat(total * item.exchange.change).toFixed(2)
            }
            return total
        },
        getPriceByCurrency(precio,coin,item) {
            var price = precio;
            // alert(item.currency);
            if (coin == '1' && item.currency == '2') {
                price = price / item.exchange;
            }
            else if (coin == '2' && item.currency == '1') {
                price = price * item.exchange;
            }
            return price;
        },
        shipping(item) {
            if(this.free) {
                return 0
            }
            if(item.shipping_fee == 0) {
                return parseInt(this.getPriceByCurrency(this.getShippingFee(item), 2,item));
            } 
            
            if(item.shipping_fee == 1) {
                return parseInt(this.getPriceByCurrency(this.getShippingFee(item), 2,item));
            }
            
            // if(shipping_selected == 1) {
            //     return this.getPriceByCurrency(item.amount, 2)
            // }

            return 0
            },
        comission(item){
            let total = this.getTotal2(item)
            // alert(subtotal);
            // if(this.hasMinimumPurchaseDiscount()) {
            //     subtotal = subtotal - this.minimumPurchaseDiscount
            // }

            // alert(subtotal);
            // if(this.quantityDiscount) {
            //     subtotal = subtotal - this.quantityPurchaseDiscount
            // } 

            let comission = (total+this.shipping(item)) * 0.055 + 0.3;
            // alert(comission);
            
            return this.getPriceByCurrency(comission, '2',item)
            // return comission
        },
        _search () {
            if((this.init && !this.end) || (!this.init && this.end)) {
                swal('', 'Debe seleccionar una fecha de inicio y de fin', 'error')
                return
            }   

            if (this.submitted) {
                return;//
            }
            this.submitted = true;
            showLoading()
            axios.post(`admin/purchases/search?page=${this.page}`, { 
                init: this.init, 
                end: this.end, 
                status: parseInt(this.status),
                search: this.search || ''
            })
                .then(res => {
                    const rawData = res.data.data.map(e => {
                        return {
                            ...e,
                            code: e.payment_type == 4 ? e.transaction_code : e.transfer ? e.transfer.number : '',
                            deliveryType: e.delivery.type ? e.delivery.type == '1' ? 'Envio Nacional (Cobro a Destino)' : 'Envio Nacional (Envio a Tienda)' : 'Envio Regional',
                            statusType: e.status == 0 ? 'En espera' : e.status == 1 ? 'Procesando' : e.status == 2 ? 'Cancelado' : 'Completado',
                        }
                    })
                    this.pages = res.data.last_page
                    this.dataTable = [...rawData];
                })
                .catch(err => {
                    swal('', 'Algo ha sucedido', 'error')
                    console.log(err)
                })
                .finally(() => {
                    this.submitted = false;
                    quiLoading()
                });
        },
        
        getEnd(date){
            this.endErr = false
            
            if (this.init && moment(date).isBefore(moment(this.init))) {
                swal('', 'No puedes poner una fecha anterior de la de inicio, vuelva a seleccionarla')
                this.endErr = true
                this.init = ""
                return
            }
            this.end = moment(date).format('Y-MM-DD')
        },
        
        getInit(date){
            this.initErr = false
            
            if (this.end && moment(date).isAfter(moment(this.end))) {
                swal('', 'No puedes poner una fecha superior a la de fin, vuelva a seleccionarla')
                this.initErr = true
                return false
            }
            this.init = moment(date).format('Y-MM-DD')
        },

        verify () {
            if(this.initErr) {
                document.querySelector('#date_picker_init').value = ""
                this.init = ""
            }

            if(this.endErr) {
                document.querySelector('#date_picker_end').value = ""
                this.end = ""
            }
        },

        _approve(item) {
            this.statusName = 'Aprobar';
            this.modalApprove.type.confirm = true;
            this.modalApprove.type.action = this._approveConfirm;
            this.modalApprove.data = item;
            this.modalApprove.action = this._approveConfirm
            this.modalApprove.init.open();
        },

        _completed(item) {
            this.statusName = 'Completar';
            this.modalApprove.type.confirm = true;
            this.modalApprove.type.action = this._completedConfirm;
            this.modalApprove.data = item;
            this.modalApprove.action = this._completedConfirm
            this.modalApprove.init.open();
        },

        _completedConfirm() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalApprove.data.id
            });

            var status = 3;
            this.modalApprove.init.close();

            showLoading();
            axios.post(`admin/purchases/approve/${this.modalApprove.data.id}`, { status: status})
            .then(res => {
                this.dataTable[index].status = status;
                this.dataTable[index].statusType = 'Completado';
                swal("", 'Se completo el pedido de manera exitosa', "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            })
            .finally(() => {
                quiLoading();
            });
        },

        _approveConfirm() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalApprove.data.id
            })
            var status = 1;
            this.modalApprove.init.close();
            showLoading();
            axios.post(`admin/purchases/approve/${this.modalApprove.data.id}`, {status:status})
            .then(res => {
                this.dataTable[index].status = status;
                this.dataTable[index].statusType = 'Procesando';
                swal("", 'Se aprobo el pedido de manera exitosa', "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            })
            .finally(() => {
                quiLoading();
            });
        },

        _reject(item) {
            this.modalReject.type.confirm = true;
            this.modalReject.type.action = this._rejectConfirm;
            this.modalReject.data = item;
            this.modalReject.action = this._rejectConfirm
            this.modalReject.init.open();
        },

        _rejectConfirm() {
            let index = this.dataTable.findIndex(e => {
                return e.id == this.modalReject.data.id
            })
            var status = 2;
            this.modalReject.init.close();
            showLoading();
            axios.post(`admin/purchases/reject/${this.modalReject.data.id}`, {status:status, rejectReason: this.modalReject.data.rejectReason})
            .then(res => {
                this.dataTable[index].status = status;
                this.dataTable[index].statusType = 'Cancelado';
                swal("", 'Se rechazo el pedido de manera exitosa', "success");
            })
            .catch(err => {
                this._showAlert('Disculpa, ha ocurrido un error', "error")
            })
            .finally(() => {
                quiLoading();
            });
        },

        padZeros(num) {
            return num.toString().padStart(5, "0");
        }
    },
    mounted() {
        // this.purchases.forEach(e => {
        //     if(e.payment_type == 5) {
        //         e.code = ''
        //     }else if (e.payment_type == 4){
        //         e.code = e.transaction_code ;
        //     } else { // Zelle, transfer y stripe
        //         e.code = e.transfer.number
        //     }
        //     e.deliveryType = e.delivery.type ? e.delivery.type == '1' ? 'Envio Nacional (Cobro a Destino)' : 'Envio Nacional (Envio a Tienda)' : 'Envio Regional';
        //     e.statusType = e.status == 0 ? 'En espera' : e.status == 1 ? 'Procesando' : e.status == 2 ? 'Cancelado' : 'Completado';
        // })
        // // console.log(this.purchases, 'purchasse')
        // this.dataTable = this.purchases
        // this.fullPurchases = this.purchases
        setTimeout(() => {
            M.Datepicker.init(document.querySelector('#date_picker_init'), {
                format: "yyyy-mm-dd",
                onSelect: this.getInit,
                minYear: minYear,
                maxYear: maxYear,
                onClose: this.verify,
                i18n: pickDateI18n
            });
        }, 100);

        setTimeout(() => {
            M.Datepicker.init(document.querySelector('#date_picker_end'), {
                format: "yyyy-mm-dd",
                minYear: minYear,
                maxYear: maxYear,
                onSelect: this.getEnd,
                onClose: this.verify,
                i18n: pickDateI18n
            });


        }, 100);
        this._search()
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
        this.modalApprove.init = M.Modal.init(document.querySelector('#approve-purchase-modal'));
        this.modalReject.init = M.Modal.init(document.querySelector('#reject-purchase-modal'));
    }
}
</script>
