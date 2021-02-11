<template id="template-wholesaler-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col m8 offset-m2">
                <div class="row">
                    <div class="col s12 center-align">
                        <h1>Editar Colección Jurídica</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <a :href="'admin/wholesalers' | asset" class="btn btn-back" @click="setOption(1)">
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
                                    class="browser-default input-impegno" min="1">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="description_english" class="label-impegno">Cantidad existente</label>
                                <input 
                                    type="number" 
                                    name="name" id="name" 
                                    v-model="form.quantity" 
                                    class="browser-default input-impegno" min="0">
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
                                    <input-file :disabled="uploadPercentage != 0" :file="`img/products/${form.main}` | img" :btn="false" :image="true" @file="_setFile(null, null, $event)" ></input-file>
                                    <div class="col s4"></div>
                                    <div class="col s4">
                                        <div class="progress" id="principal" v-if="form.main">
                                            <div class="determinate" :style="`width: ${uploadPercentage}%`"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gallery__items">
                                <div class="col s12 container-btn-add">
                                    <button class="btn-add" @click="_addImage()">
                                        <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                                    </button>
                                    <div class="btn-add-text">
                                        Agregar nueva imagen secundaria
                                    </div>                            
                                </div>
                                <div class="col l4 m6 s6 items__file" :key="index" v-for="(file, index) in form.images" :id="`file-${file.id}`">
                                    <input-file :file="file.file !== '' ? `${urlBase + 'img/products/' + file.file}` : ''" :btn="false" :image="true" @file="_setFile(file.id, index, $event)"  :disabled="file.disabled"></input-file>
                                    <button class="file__claer" @click="_sliceItem(file.id, index)" :disabled="file.disabled"></button>
                                    <div class="progress" :id="'progress-' + index">
                                        <div class="determinate" :style="`width: ${file.uploadPercentage}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m12 l12 margin-top center-align">
                    <a href="#!" class="btn btn-success" @click="update" :disabled="sending">Guardar</a>
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
    .progress {
        opacity: 0;
        transition: all ease-in-out 0.35s;
    }
    .progress-active {
        opacity: 1;
    }
</style>


<script>
import { mapState } from 'vuex'


export default {
    props: {
        wholesaler: {
            type: Object,
            default: {}
        }
    },
    data () {
        return {
            uploadPercentage: 0,
            sending: false,
            urlBase: urlBase,
            form: {
                filter_id: '',
                name: null,
                name_english: null,
                quantity: null,
                coin: '1',
                price: null,
                description: null,
                description_english: null,
                images: [],
                main: "",
                _method: 'PUT'
            },
            images: [],
            image: "",
            ids: 0,
            filters: [],
        }
    },
    computed: 
        mapState({
            option: state => state.wholesalers.option
    }),
    methods: {
        _addImage() {
            this.form.images.push({file: "", id: 0, uploadPercentage: 0})
            this.images = this.form.images
            this.elements = this.elements + 1
        },
        _setFile(i, x, e) {
            if(e.file.type.match("video.*")) {
                return swal('', 'Solo se aceptan imagenes', 'error')
            }

            var selector = ''
            if(x == null) {
                selector = '#principal'
            } else {
                selector = `#progress-${x}`
            }
            let progressElement = document.querySelector(selector)
            progressElement.classList.add('progress-active')
            this.sending = true

            let formData = new FormData()
            formData.append('id',  i)
            formData.append('file', e.file)
            formData.append('wholesaler_id', this.form.id)
            axios.post('admin/wholesalers/update-images', formData, {
                onUploadProgress: function( progressEvent ) {
                    this.sending = true
                    if(x == null) {
                        this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                    } else {
                        this.form.images[x].uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                        this.form.images[x].disabled = true
                   }
                }.bind(this)
            })
                .then(resp => {
                    this.sending = false
                    if (i != null) {
                        this.form.images[x].id = resp.data.id
                        this.form.images[x].file = resp.data.file
                    }
                    this._quitProgress(progressElement, x)
                })
                .catch(err => {
                    this.sending = false
                    console.log(err)
                    this._quitProgress(progressElement, x)
                })
        },
         _quitProgress(progressElement, x) {
            progressElement.classList.remove('progress-active')
            setTimeout(() => {
                if(x != null) {
                    this.form.images[x].uploadPercentage = 0
                    this.form.images[x].disabled = false
                }else {
                    this.uploadPercentage = 0
                }
            }, 500)
        },
        _sliceItem (id, i) {
            this.form.images[i].disabled = true
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)            
            this.form.i
            if (id != 0) {
                axios.post('admin/wholesalers/delete-images', {id: id})
                .then(resp => {
                    parent.removeChild(child)
                    this.elements = this.elements - 1
                })
                .catch(err => {
                    this.form.images[i].disabled = false
                    this._showAlert("Disculpa, ha ocurrido un error", "error")
                })
            } else {
                parent.removeChild(child)
                this.elements = this.elements - 1
            }
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
        update () {
            console.log(this._convertToFormData())
            axios.put(`admin/wholesalers/${this.wholesaler.id}`, this.form)
                .then(res => {
                    console.log(res)
                    this._showAlert('Se actualizó la colección correctamente', 'success')
                    setTimeout(() => {
                        window.location = urlBase + 'admin/wholesalers'
                    },2000)
                })
                .catch(err => {
                    if(err.response.status === 422){
                        this._showAlert(err.response.data.error, 'warning')
                        return false;
                    }
                  
                    swal('', 'Ha ocurrido un error', 'error')
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
        },

        _convertToFormData(){
            let formData = new FormData();
            formData.append('_method', 'PATCH');
            Object.getOwnPropertyNames(this.form).forEach((key, i) => {
                let count = 0;
                if(key === "images") { 

                }else if(key != "__ob__"){
                    
                    formData.append(key, this.form[key]);
                                      
                }
            });

            return formData;
        },
    },
    mounted () {
        this.getFilters()
        this.form = this.wholesaler
        let images = new Array()
        this.form.images.forEach(el => {
            Vue.set(el, 'uploadPercentage', 0) 
            Vue.set(el, 'disabled', false)  

            if (el.main == "1") {
                this.form.main = el.file                
            } else {
                images.push(el)
            }
        })
        this.form.images = images
    }
}
</script>
