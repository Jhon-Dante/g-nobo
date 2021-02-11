<template id="template-social-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Información de Contacto</h1>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <section class="table__content">
                    <table-byte :set-table="setTable" :filters="['facebook', 'instagram', 'youtube', 'slogan', 'address', 'phone', 'email']">
                        <table-row slot="table-head">
                            <!-- <table-head class="th">Slogan </table-head> -->
                            <table-head>Dirección</table-head>
                            <table-head>Teléfono</table-head>    
                            <table-head>Correo</table-head>    
                            <table-head>Acción</table-head>
                        </table-row>

                        <table-row slot="table-row" slot-scope="{ item }">
                            
                            <!-- <table-cell>{{ item.slogan }}</table-cell> -->
                            <table-cell>{{ item.address }}</table-cell>
                            <table-cell>{{ item.phone }}</table-cell>
                            <table-cell>{{ item.email }}</table-cell>
                            <table-cell class="icon-margin">
                                <a href="#!" class="btn-action" @click="_view(item)" title="Ver">
                                    <img :src="'img/icons/ico-ver.png' | asset" alt="" class="img-responsive">
                                </a>
                                <a :href="`${url}/${item.id}/edit`" class="btn-action" title="Editar">
                                    <img :src="'img/icons/ico-editar.png' | asset" alt="" class="img-responsive">
                                </a>
                            </table-cell>
                        </table-row>

                        <table-row slot="empty-rows">
                            <table-cell colspan="5">
                                No se encontraron registros.
                            </table-cell>
                        </table-row>

                    </table-byte>
                    
                    <byte-modal :confirm="modal.type.confirm">
                        <template v-if="modal.type.action == 'view'">
                            <div class="col s12">
                                <h3>Detalles</h3>
                            </div>
                            <div class="col s12 m6">
                                <div class="col s12">
                                    <h3>Facebook</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.facebook }}
                                </div>
                                <div class="col s12">
                                    <h3>Instagram</h3>
                                </div>      
                                <div class="col s12">
                                    {{ modal.data.instagram }}
                                </div>
                            </div>
                            <!-- <div class="col s12">
                                <!-- <div class="col s12">
                                    <h3>Youtube</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.youtube }}
                                </div> 
                                <div class="col s12">
                                    <h3>Slogan</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.slogan }}
                                </div>
                            </div>               -->
                            <div class="col s12">
                                <div class="col s12">
                                    <h3>Dirección</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.address }}
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="col s12">
                                    <h3>Teléfono</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.phone }}
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="col s12">
                                    <h3>Correo</h3>
                                </div>
                                <div class="col s12">
                                    {{ modal.data.email }}
                                </div>
                            </div>
                        </template>
                    </byte-modal>
                
                </section>
            </div>
        </div>


    </section>
</template>

<script>
export default {
    template: "#template-social-index",

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
        _view(item) {
            this.modal.type.confirm = false,
            this.modal.type.action = "view",
            this.modal.data = item,
            this.modal.init.open() 
        }
    },
    props: {
        url: {
            type: String,
            default: ""
        },

        posts: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    mounted() {
        this.setTable = this.posts
        this.modal.init = M.Modal.init(document.querySelector('.modal'));
    }
}
</script>
