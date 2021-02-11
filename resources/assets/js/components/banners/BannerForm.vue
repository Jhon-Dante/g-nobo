<template id="template-banner-index">
    <section class="container-fluid">

        <div class="row">
            <div class="col s12 center-align">
                <h1>Banners</h1>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <section class="table__content">
                    <div class="row">
                        <a :href="url + '/create'" class="col s12 container-btn-add">
                            <button class="btn-add">
                                <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nuevo
                            </div>       
                        </a>
                    </div>

                    <table-byte :set-table="setTable" :filters="['title']">
                        <table-row slot="table-head">
                            <table-head>Imagen</table-head>
                            <table-head class="th">Título</table-head>
                            <table-head>Acción</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            <table-cell>
                                <img :src="item.img" alt="">
                            </table-cell>
                            <table-cell>{{ item.title }}</table-cell>
                            <table-cell class="icon-margin">

                                <a href="#!" class="btn-action" @click="_view(item)">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>

                                <a :href="`${url}/${item.id}/edit`" class="btn-action">
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
                                <h3>Detalles del Blog</h3>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Título</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.title }}
                                </div>
                                <div class="col s12">
                                    <h3>Descripción</h3>
                                </div>      
                                <div class="col s12">
                                    {{ modal.data.description }}
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Título (Inglés)</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.title_english }}
                                </div>
                                <div class="col s12">
                                    <h3>Descripción (Inglés)</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.description_english }}
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="container-confirmation">
                                <div class="confimation__icon">
                                    <i class="material-icons">error_outline</i>
                                </div>
                                <div class="confirmation__text">
                                    <h5>¿ Realmente deseas <b>Eliminar</b> éste Blog ?</h5>
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                
                </section>
            </div>
        </div>


    </section>
</template>
<style>
    .icon-margin{
        width: 195px;
    }

    .img-width{
        width:100%;
        height: 300px;
        object-fit: contain;
    }

    .th {
        width: 200px;
    }
</style>

<script>
export default {
    template: "#template-banner-index",

    data(){
        return {
            setTable: [],
            modal: {
                init: {},
                title: "",
                type: {
                    confirm: false,
                    action: "view"
                },
                data: {}
            }
        }
    },

    methods: {
        _view(item){
            this.modal.type.confirm = false;
            this.modal.type.action = "view";
            this.modal.data = item;
            this.modal.init.open();
        },

        _confirm(item){
            this.modal.type.confirm = true;
            this.modal.type.action = "destroy";
            this.modal.data = item;
            this.modal.init.open();
        },
        _delete() {
            axios.delete(
                "/admin/blogs/"+this.modal.data.id
            ).then(
                resp=> {
                    this.modal.init.close();
                    swal({
                        title: '',
                        text: 'El blog ha sido borrado exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: 'success'
                    },
                    ()=>{
                        window.location = urlBase+"/admin/blogs"
                    });
                }
            ).catch(
                err=> {
                    let message = "Disculpe, ha ocurrido un error";
                    if (err.response.status == 422) {
                        message = err.response.data.error;
                    }
                    swal("asd", message,"error");
                }
            )
        }
    },
    
    props: {
        url: {
            type: String,
            default: ""
        },
        banners: {
            type: Array,
            default(){
                return [];
            }
        }
    },
    
    mounted() {
        this.setTable = this.banners;
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
    }
}
</script>