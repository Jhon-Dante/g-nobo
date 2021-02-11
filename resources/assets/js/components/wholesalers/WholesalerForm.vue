<template id="template-wholesaler-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col m8 offset-m2">
                <div class="row">
                    <div class="col s12 center-align">
                        <h1>Crear Colección Jurídica</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <a href="#!" class="btn btn-back" @click="setOption(1)">
                            <div class="btn-back__container">
                                <div class="btn-back__ico"></div>
                                <label for=""> Volver</label>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s6"><a class="active" href="#test1">Información</a></li>
                            <li class="tab col s6"><a href="#test3">Imágenes</a></li>
                        </ul>
                    </div>
                    <div id="test1" class="col s12">
                        <div class="row container-form">
                            <!-- <div class="col s12">
                                <p><b>Nota:</b> El nombre (Español/Inglés) se irá construyendo con los campos que seleccionará a continuación.</p>
                            </div> -->
                            

                            <div class="col s12 m6 l6 center-align">
                                <label for="name_english" class="label-impegno">Nombre (Español)</label>
                                <input 
                                    type="text" 
                                    name="name" id="name" 
                                    v-model="form.name" 
                                    class="browser-default input-impegno">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="description_english" class="label-impegno">Nombre (Ingles)</label>
                                <input 
                                    type="text" 
                                    name="name" id="name" 
                                    v-model="form.name_english" 
                                    class="browser-default input-impegno">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="name_english" class="label-impegno">Precio</label>
                                <input 
                                    type="number" 
                                    name="name" id="name" 
                                    v-model="form.price" 
                                    class="browser-default input-impegno">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="description_english" class="label-impegno">Cantidad existente</label>
                                <input 
                                    type="number" 
                                    name="name" id="name" 
                                    v-model="form.quantity" 
                                    class="browser-default input-impegno">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="description" class="label-impegno">Descripción (Español)</label>
                                <textarea name="description" id="description" v-model="form.description" class="browser-default textarea-impegno"></textarea>
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="description_english" class="label-impegno">Descripción (Inglés)</label>
                                <textarea name="description_english" id="description_english" v-model="form.description_english" class="browser-default textarea-impegno"></textarea>
                            </div>
                            <div class="col s12 m6 l6 center-align">
                                <label for="filter_id" class="label-impegno">Disponible para</label>
                                <select name="filter_id" id="filter_id" class="browser-default" v-model="form.filter_id">
                                    <option value="">Seleccione</option>
                                    <option :value="id" :key="id" v-for="(filter, id) in filters">{{ filter }}</option>
                                </select>
                            </div>
                            <div class="col s12 m6 l6 center-align container-options">
                                <label class="label-impegno">Moneda</label>
                                <p>
                                    <label>
                                        <input name="group1" value="1" type="radio" v-model="form.coin" />
                                        <span>Bs.</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="group1" value="2" type="radio" v-model="form.coin" checked />
                                        <span>USD</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="test3" class="col s12">
                        <div class="row container-form">
                            <div class="row">
                                <div class="col s12 center-align">
                                    <label for="" class="label-impegno">Imagen Principal</label>
                                    <input-file :btn="false"   image v-on:file="setImage(null, $event)"></input-file>
                                </div>
                            </div>
                            <div class="row gallery__items">
                                <div class="col s12 container-btn-add">
                                    <button class="btn-add" @click="addImage()">
                                        <img :src="'img/icons/new-msg.png' | asset" 
                                            alt="" 
                                            class="img-responsive">
                                    </button>

                                    <div class="btn-add-text">
                                        Agregar nueva imagen secundaria
                                    </div>                            
                                </div>
                                <div class="col l4 m6 s6 items__file" 
                                    :key="index" v-for="(file, index) in form.images" 
                                    :id="`file-${file.id}`">

                                    <input-file 
                                        :btn="false" 
                                        image 
                                        v-on:file="setImage(index, $event)">
                                    </input-file>

                                    <button 
                                        class="file__claer" 
                                        @click="quitImage(file.id, index)">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m12 l12 margin-top center-align">
                    <a href="#!" class="btn btn-success" @click="store" :disabled="sending">Guardar</a>
                    <!-- <a href="#!" class="btn btn-success" @click="update" v-else>Actualizar</a> -->
                </div>
            </div>
        </div>
    </section>
</template>

<style>
 .container-form{
        background-color: #fff;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 1rem .75rem;
    }
</style>


<script>
import { mapState } from 'vuex'

export default {
    data () {
        return {
            form: {
                filter_id: '',
                name: '',
                name_english: '',
                quantity: '',
                coin: '1',
                price: '',
                description: '',
                description_english: '',
                images: [],
                main: ""
            },
            images: [],
            image: "",
            ids: 0,
            filters: [],
        }
    },
    computed: 
        mapState({
            option: state => state.wholesalers.option,
            sending: state => state.wholesalers.sending
    }),
    methods: {
        addImage () {
            this.ids = this.form.images.length > 1 ? this.ids + 1 : this.ids
            this.form.images.push({file: "", id: this.ids})
            this.images = this.form.images
        },

        quitImage (id, i) {            
            this.images = this.form.images.filter((el) => {
                return (el.id != id)
            })
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)            
            parent.removeChild(child)
        },
        setImage(i, file) {

            if(file.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }

            if (i == null) {
                this.image = file.file
                this.form.main = file.file
            }else {
                this.form.images[i].file = file.file
                this.files = this.form.images
            }            
        },
        setOption (option) {
            this.$store.dispatch('wholesalers/changeOption', option)
        },
        getFilters () {
            axios.get('admin/filters/get')
                .then(res => {
                    this.filters = res.data
                })
                .catch(err => {
                    console.log(err)
                }) 
        },
        store () {
            this.$store.dispatch('wholesalers/addWholesaler',  this.convertToFormData())
        },
        convertToFormData(){
            var formData = new FormData();
            Object.getOwnPropertyNames(this.form).forEach((key, i) => {
                let count = 0;
                if(key === "images")
                {
                    this.images.forEach((e, y) => {
                        if (e.file !== "") {
                            count = count + 1
                            formData.append(`file${count}`, e.file);
                        }                        
                    })
                    formData.append('count', count)
                }else if(key != "__ob__"){
                    formData.append(key, this.form[key]);                       
                }
            });

            return formData;
        }
    },
    mounted () {
        this.getFilters()
    }
}
</script>
