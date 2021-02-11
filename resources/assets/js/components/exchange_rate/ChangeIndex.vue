<template id="template-change-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1 v-if="options == 0">Tasa de Cambio</h1>
                <h1 v-if="options == 1">Nueva Tasa de Cambio</h1>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content" v-if="options == 0">

                    <!-- <div class="row">
                        <div class="col s12 container-btn-add">
                            <button class="btn-add" @click="options = 1">
                                 <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nuevo
                            </div>                            
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col s12 m6 l6  offset-l3  offset-m3 center-align">
                            <label for="change" class="label-impegno">Tasa de cambio</label>
                            <input type="number" name="change" id="name_english" v-model="form.change" step="any" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m12 l12 margin-top center-align">
                            <a href="#!" class="btn btn-success" @click="_store($event)" >Guardar</a>
                        </div>
                    </div>

                    <div class="row">
                        <table-byte class="col s12" :set-table="dataTable" :filters="['change']">
                            <table-row slot="table-head" slot-scope="{ item }">
                                <table-head>Tasa de cambio</table-head>
                                <table-head>Fecha</table-head>
                            </table-row>

                            <table-row slot="table-row" slot-scope="{ item }">
                                <table-cell>{{ item.change }}</table-cell>
                                <table-cell>{{ item.date }}</table-cell>
                            </table-row>

                            <table-row slot="empty-rows">
                                <table-cell colspan="3">
                                    No se encontraron registros.
                                </table-cell>
                            </table-row>

                        </table-byte>
                    </div>
                </section>
                <change-form v-if="options == 1" @back="_resetView"></change-form>
            </div>
        </div>
    </section>
</template>

<style lang="scss">
    .btn{
        margin-top: 1rem;
    }
</style>

<script>
export default {
    props: {
        changes: {
            type: Array,
            default: []
        }
    },

    created () {
        this.dataTable = this.changes        
    },

    data () {
        return {
            form: {
                id: 0,
                change: 0.00
            },
            dataTable: [],
            options: 0
        }
    },

    methods: {
        _resetView (option) {
            this.options = option
        },

        _store (e) {
            e.preventDefault()
            if (this.form.change == "" || this.form.change == 0) {
                this._showAlert("Disculpa, debes cargar la tasa de cambio", "warning")
                return false
            }
            e.target.setAttribute('disabled', true);

            axios.post('admin/exchange_rate', this.form)
                .then(res => {
                    this._showAlert(res.data.message, "success");

                    this.dataTable.unshift(res.data.change);

                    this.form.change = 0;
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 3001);
                    
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";
                    this._showAlert(message, "error")
                })
                .then(all => {
                    e.target.removeAttribute('disabled')
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
        
    }
}
</script>

