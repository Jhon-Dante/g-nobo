<template id="template-collection-index">

    <section class="container-fluid">

        <div class="row">
            <div class="col s12 center-align">
                <h1>Colecciones</h1>
            </div>
        </div>

        <div class="row" v-if="options === 0">
            <div class="col s12">

                <section class="table__content">

                    <div class="row">
                        <div class="col s12 container-btn-add" @click="options = 1">
                            <button class="btn-add">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nueva
                            </div>                            
                        </div>
                    </div>

                    <table-byte :set-table="setTable" :filters="['name', 'name_english']">
                        <table-row slot="table-head" slot-scope="{ item }">
                            <table-head>Nombre (Español)</table-head>
                            <table-head>Nombre (Ingles)</table-head>
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

                    <!-- Modal -->
                    <byte-modal v-on:pressok="_delete" :confirm="modal.type.confirm">
                        <div class="container-confirmation">
                            <div class="confimation__icon">
                                <i class="material-icons">error_outline</i>
                            </div>
                            <div class="confirmation__text">
                                <h5>¿Realmente desea <b>eliminar</b> la colección <b>{{ modal.data.name }}</b> ?</h5>
                            </div>
                        </div>
                    </byte-modal>

                </section>
            </div>
        </div>

        <div class="row" v-if="options !== 0">
            <collection-form v-if="options === 1"></collection-form>
            <collection-form v-if="options === 2" :set-form="editItem"></collection-form>
        </div>
    </section>   
</template>

<script>

import CollectionForm from "./CollectionForm";

export default {
    template: "#template-collection-index",
    components: { CollectionForm },
    props: {
        url: {
            type: String,
            default: ""
        },
        collections: {
            type: Array,
            default(){
                return []
            }
        }
    },
    data() {
        return {
            options: 0,
            setTable: [],
            editItem: {},
            modal: {
                init: {},
                type: {
                    confirm: false,
                    action: 'view'
                },
                data: {}
            }
        }
    },
    methods:{
        _edit(data, action){
            this.editItem = data;
            this.options = 2;
        },

        _confirm(data, action){
            this.modal.type.action = action;
            this.modal.type.confirm = true;
            this.modal.data = data;
            this.modal.init.open();
        },

        _delete() {
            let index = this.setTable.findIndex(e => {
                return e.id == this.modal.data.id
            })

            this.modal.init.close();

            axios.delete(`admin/collections/${this.modal.data.id}`)
                .then(res => {
                    if(res.data.result){
                        this.setTable.splice(index, 1)
                        swal('', 'Se ha eliminado la colección', "success");
                        return;
                    }

                    swal('', res.data.error, "error");
                })
                .catch(err => {
                    swal('', 'Disculpe, ha ocurrido un error', "error")
                });
        }
    },
    mounted () {
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
        this.setTable = this.collections;
    }
}
</script>
