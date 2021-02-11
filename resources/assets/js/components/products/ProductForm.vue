<template id="template-product-form">
    <div class="col s12">
        <div class="row">
            <div class="col s12">
                <a href="#!" class="btn btn-back" @click="_back()">
                    <div class="btn-back__container">
                        <div class="btn-back__ico"></div>
                        <label for=""> Volver</label>
                    </div>
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col" :class="[form.variable == 1 ? 's4' : 's6']"><a class="active" href="#test1">Información</a></li>
                        <li class="tab col s4" v-show="form.variable == 1"><a href="#test2">Presentaciones</a></li>
                        <li class="tab col" :class="[form.variable == 1 ? 's4' : 's6']"><a href="#test3">Imágenes</a></li>
                    </ul>
                </div>
                <div id="test1" class="col s12">
                    <div class="row container-form">
                        <!-- <div class="col s12">
                            <p><b>Nota:</b> El nombre (Español/Inglés) se irá construyendo con los campos que seleccionará a continuación.</p>
                        </div> -->

                        <div class="col s12 m12 l12 center-align container-options">
                            <label class="label-impegno">Tipo de producto</label>
                            <p>
                                <label>
                                    <input name="variable" value="0" type="radio" v-model="form.variable"/>
                                    <span>Producto Simple</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="variable" value="1" type="radio" v-model="form.variable" />
                                    <span>Producto Varible</span>
                                </label>
                            </p>                           
                        </div>

                        <div class="col s12 m12 l12 center-align container-options">
                            <label class="label-impegno">¿Desea marcar este producto como Destacado?</label>
                            <p>
                                <label>
                                    <input name="pro" value="1" type="radio" v-model="form.pro" />
                                    <span>Si</span>
                                </label>
                            </p>  
                            <p>
                                <label>
                                    <input name="pro" value="0" type="radio" v-model="form.pro"/>
                                    <span>No</span>
                                </label>
                            </p>                         
                        </div>
                        
                        <div class="col s12 m6 l6 center-align">
                            <label for="name" class="label-impegno">Nombre (Español)</label>
                            <input type="text" name="name" id="name" v-model="form.name" maxlength="50" class="browser-default input-impegno">
                            <!-- <p class="names">{{ form.name }}</p> -->
                        </div>

                        <!--<div class="col s12 m6 l6 center-align">
                            <label for="name_english" class="label-impegno">Nombre (Inglés)</label>
                            <input type="text" name="name_english" id="name_english" v-model="form.name_english" maxlength="50" class="browser-default input-impegno">
                             <p class="names">{{ form.name_english }}</p> 
                        </div> -->

                        <div class="col s12 m6 l6 center-align">
                            <label for="category_id" class="label-impegno">Categoría</label>
                            <select name="category_id" id="category_id" class="browser-default" @change="_setSubcategories($event);" v-model="form.category_id">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in categories">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="subcategory_id" class="label-impegno">Subcategoría</label>
                            <select name="subcategory_id" id="subcategory_id" class="browser-default" v-model="form.subcategory_id">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in subcategories">{{ item.name }}</option>
                            </select>
                        </div>
                        <!-- <div class="col s12 m6 l6 center-align">
                            <label for="collection_id" class="label-impegno">Colección</label>
                            <select name="collection_id" id="collection_id" class="browser-default" v-model="form.collection_id" @change="_setDedings($event); _constructNames($event, 2, collections)">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in collections">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="design_id" class="label-impegno">Diseños</label>
                            <select name="design_id" id="design_id" class="browser-default" v-model="form.design_id" @change="_constructNames($event, 3, arrayDesigns)">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in arrayDesigns">{{ item.name }}</option>
                            </select>
                        </div> -->

                        <div class="col s12 m6 l6 center-align">
                            <label for="description" class="label-impegno">Descripción (Español)</label>
                            <textarea name="description" id="description" v-model="form.description" class="browser-default textarea-impegno"></textarea>
                        </div>

                        <!-- <div class="col s12 m6 l6 center-align">
                            <label for="description_english" class="label-impegno">Descripción (Inglés)</label>
                            <textarea name="description_english" id="description_english" v-model="form.description_english" class="browser-default textarea-impegno"></textarea>
                        </div>-->
                        <!-- <div class="col s12 m6 l6 center-align container-options">
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
                        </div> -->
                        <template v-if="form.variable != 1">
                            <div class="col s12 m6 l6 center-align">
                                <label for="name_english" class="label-impegno">Precio $</label>
                                <input type="number" name="price_1" id="price_1" v-model="form.price_1" class="browser-default input-impegno" min="1">
                                <!-- <label for="name_english" class="label-impegno">Precio (Mayor)</label>
                                <input type="text" name="price_2" id="price_2" v-model="form.price_2" class="browser-default input-impegno" min="1"> -->
                            </div>
                            <div class="col s12 m6 l6 center-align">
                                <label for="name_english" class="label-impegno">Cantidad en existencia</label>
                                <input type="number" name="price_2" id="price_2" v-model="form.amount" class="browser-default input-impegno" min="1">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="min" class="label-impegno">Mínimo de venta</label>
                                <input type="number" name="min" id="min" v-model="form.min" class="browser-default input-impegno" min="1">
                            </div>
                            <div class="col s12 m6 l6 center-align">
                                <label for="max" class="label-impegno">Máximo de venta</label>
                                <input type="number" name="max" id="max" v-model="form.max" class="browser-default input-impegno" min="1">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="cost" class="label-impegno">Costo</label>
                                <input type="number" name="cost" id="cost" v-model="form.cost" class="browser-default input-impegno" min="1">
                            </div>

                            <div class="col s12 m6 l6 center-align">
                                <label for="umbral" class="label-impegno">Umbral</label>
                                <input type="number" name="umbral" id="umbral" v-model="form.umbral" class="browser-default input-impegno" min="1">
                            </div>   
                                                     
                        </template>

                        <div class="col s12 m6 l6 center-align">
                            <label for="taxe_id" class="label-impegno">Impuesto</label>
                            <select name="taxe_id" id="taxe_id" class="browser-default"  v-model="form.taxe_id">
                                <option value="">Excento</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in taxes">{{ item.name }}</option>
                            </select>
                        </div>

                        
                        
                        <!-- <div class="col s12 m6 l6 center-align container-options">
                            <label class="label-impegno">Tipo de venta</label>
                            <p>
                                <label>
                                    <input type="checkbox" id="retail" class="filled-in" checked="checked" value="1" v-model="form.retail" />
                                    <span>Detal</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" id="wholesale" class="filled-in" value="1" v-model="form.wholesale" />
                                    <span>Mayor</span>
                                </label>
                            </p>
                        </div> -->
                    </div>
                </div>
                 <div id="test2" class="col s12">
                   <div class="row container-form">
                        <div class="col s12 container-btn-add">
                            <button class="btn-add" @click="_addPresentation()">
                                 <img :src="'img/icons/new-msg.png' | asset" alt="" class="img-responsive">
                            </button>
                            <div class="btn-add-text">
                                Agregar nueva
                            </div>                            
                        </div> 
                        <div class="col s12" v-for="(item, index) in form.presentations" :key="index">
                            <fieldset>
                                <button class="btn-remove btn-remove--small" @click="_removePresentation(index)">
                                    <img :src="'img/icons/cancelar_white.png' | asset" alt="" class="img-responsive">
                                </button>
                                <div class="row">
                                    <h5 class="label-impegno mt-2 center-align">Nueva presentación</h5>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="name" class="label-impegno">Unidad</label>
                                        <!-- <input type="text" name="name" id="name" v-model="item.unit" class="browser-default input-impegno input-impegno--small"> -->
                                        <select
                                            name="unit"
                                            id="unit"
                                            class="browser-default select--small"
                                            v-model="item.unit"
                                        >
                                            <option
                                                :value="item.id"
                                                :key="index"
                                                v-for="(item, index) in unities"
                                                >
                                                {{ item.id }} - {{ item.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="presentation" class="label-impegno">Presentación</label>
                                        <input type="number" name="presentation" id="presentation" v-model="item.presentation" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="amount" class="label-impegno">Cantidad</label>
                                        <input type="number" name="amount" id="amount" v-model="item.amount" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="price" class="label-impegno">Precio $</label>
                                        <input type="number" name="price" id="price" v-model="item.price" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="cost" class="label-impegno">Costo</label>
                                        <input type="number" name="cost" id="cost" v-model="item.cost" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="min" class="label-impegno">Min. Venta</label>
                                        <input type="number" name="min" id="min" v-model="item.min" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="max" class="label-impegno">Max. Venta</label>
                                        <input type="number" name="max" id="max" v-model="item.max" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                    <div class="col s12 m3 l3 center-align">
                                        <label for="umbral" class="label-impegno">Umbral</label>
                                        <input type="number" name="umbral" id="umbral" v-model="item.umbral" maxlength="50" class="browser-default input-impegno input-impegno--small">
                                    </div>
                                </div>                                
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div id="test3" class="col s12">
                    <div class="row container-form">
                        <div class="row">
                            <div class="col s12 center-align">
                                <label for="" class="label-impegno">Imagen Principal</label>
                                <input-file :btn="false" :image="true" v-on:file="_setFile(null, $event)"></input-file>
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
                                <input-file :btn="false" :image="true" v-on:file="_setFile(index, $event)"></input-file>
                                <button class="file__claer" @click="_sliceItem(file.id, index)"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12 margin-top center-align">
                <a href="#!" class="btn btn-success" @click="_store($event)" v-if="action == 0">Guardar</a>
                <a href="#!" class="btn btn-success" @click="_edit($event)" v-else>Actualizar</a>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .bold{
        font-weight: bold;
    }
    .margin-top{
        margin-top: 20px;
    }
    .container-fluid{
        width: 90%;
        margin: auto;
    }
    .container-form{
        background-color: #fff;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 1rem .75rem;
    }
    .container-options{
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        label{
            flex: 1 1 100%;
        }
    }
    fieldset{
        margin: 10px 2px !important;
        border: 1px solid #efefefec !important;
        padding: 1rem !important;
        position: relative;
    }
    h5.label-impegno{
        font-size: 1.25rem;
        color: red;
    }
    .label-impegno{
        font-weight: bold;
    }
    .items__file{
        position: relative;
    }
    .names{
        margin-bottom: 1rem;
    }
    .sizes_stop{
        margin: .5rem .2rem !important;
        border-color: rgba($color: #000000, $alpha: .3);
        border-radius: .4rem;
        legend{
            font-weight: bold;
            padding: 0 10px;
        }
    }
</style>

<script>
import { unities } from '../../constants'
export default {
    template: "#template-product-form",

    props: {
        action: {
            type: Number,
            default: 0
        },

        categories: {
            type: Array,
            default: []
        },

        designs: {
            type: Array,
            default: []
        },

        collections: {
            type: Array,
            default: []
        },

        taxes: {
            type: Array,
            default: []
        }
    },

    data () {
        return {
            subcategories: [],
            arrayDesigns: [],
            selectedCategory: {},
            objectNames: {
                spanish: [],
                english: []
            },
            inserted: [],
            form: {
                id: 0,
                name: "",
                name_english: "",
                description: "",
                description_english: "",
                coin: "2", // Dolar por defecto
                price_1: "",
                price_2: "",
                catalogue: "1",
                category_id: "",
                subcategory_id: "",
                taxe_id: "",
                collection_id: "1", // Se coloca por defecto 1
                design_id: "",
                retail: "1",
                wholesale: "1",
                colors: [],
                images: [],
                presentations: [{
                    amount: 1,
                    unit: 1,
                    presentation: 1,
                    price: 1,
                    min: 1,
                    max: 1,
                    cost: 1,
                    umbral: 1
                }],
                main: "",
                category_size_id: '',
                amount: '',
                variable: 0,
                pro: 1,
                min: 1,
                max: 1,
                cost: 1,
                umbral: 1
            },
            tabs: "",
            images: [],
            image: "",
            ids: 0,
            unities: unities
        }
    },

    methods: {
        _back() {
            this.$emit('back', 0)
        },

        _setSubcategories(e) {
            if (e.target.options[e.target.selectedIndex].value == "") {
                this.subcategories = []
            }else {
                this.selectedCategory = this.categories.find((el) => {
                    return (el.id == e.target.options[e.target.selectedIndex].value)
                })            
                if(this.selectedCategory.sizes.length > 0) {
                    this.form.category_size_id = this.selectedCategory.sizes[0].id
                }
                this.subcategories = this.selectedCategory.subcategories
            }  
        },

        _setDedings(e) {            
            if (e.target.options[e.target.selectedIndex].value == "") {
                this.arrayDesigns = []
            }else {
                this.arrayDesigns = this.designs.filter((el) => {                    
                    return (el.collection_id == e.target.options[e.target.selectedIndex].value)
                })
            }
        },

        // _addColor() {
        //     if (Object.keys(this.selectedCategory) == 0) {
        //         this._showAlert("Disculpa, debes seleccionar una categoría primero.", "warning")
        //         return false
        //     }
        //     let sizes = []
        //     this.selectedCategory.sizes.forEach(element => {
        //         sizes.push({id: element.id, name: element.name})
        //     });
        //     this.form.colors.push({name: "", sizes: sizes})
                    
        // },

        _addPresentation() {
            this.form.presentations.unshift({
                amount: 1,
                unit: 1,
                presentation: 1,
                price: 1,
                cost: 1,
                min: 1,
                max: 1,
                umbral: 1
            })
        },

        _constructNames(e, i, item) {
            let value = e.target.options[e.target.selectedIndex].value
            if (value != "") {
                let selected = item.find((el) => {
                    return(el.id == value)
                })
                if (this.inserted.indexOf(i) > -1) {
                    this.objectNames.spanish.splice(i, 1);
                    this.objectNames.english.splice(i, 1);
                    if (i == 0 || (i % 2 == 0)) {
                        this.objectNames.spanish.splice(i, 1);
                        this.objectNames.english.splice(i, 1);
                        this.inserted.splice(i + 1, 1);
                    }
                } else {
                    this.inserted.splice(i, 0, i);
                }
                this.objectNames.spanish.splice(i, 0, selected.name);
                this.objectNames.english.splice(i, 0, selected.name_english);
                this.form.name = ""
                this.form.name_english = ""
                this.objectNames.spanish.forEach((el, i) => {
                    if ((this.objectNames.spanish.length -1) == i) {
                        this.form.name += el
                    } else {
                        this.form.name += el + ' - '
                    }
                })
                this.objectNames.english.forEach((el, i) => {
                    if ((this.objectNames.english.length -1) == i) {
                        this.form.name_english += el
                    } else {
                        this.form.name_english += el + ' - '
                    }
                })      
            }
        },

        _removePresentation(i) {
            if(this.form.presentations.length == 1) {
                return
            }
            this.form.presentations.splice(i, 1);
        },
        
        _setFile(i, file) {
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
            setTimeout(function(){
              console.clear();
            }, 10);            
        },

        _addImage() {
            this.ids = this.form.images.length > 1 ? this.ids + 1 : this.ids
            this.form.images.push({file: "", id: this.ids})
            this.images = this.form.images
        },

        _sliceItem (id, i) {            
           this.images = this.form.images.filter((el) => {
                return (el.id != id)
            })
            let parent = document.querySelector(".gallery__items")
            let child = document.querySelector(`#file-${id}`)            
            parent.removeChild(child)
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

        _store (e) {
            let button = e.target
            this.form.wholesale = this.form.wholesale == false ? 0 : 1
            this.form.retail = this.form.wholesale == false ? 0 : 1
            if(this.form.variable == 1) {
                const someCostIsGreaterThanPrice = this.form.presentations.some(p => parseFloat(p.cost) > parseFloat(p.price))
                if(someCostIsGreaterThanPrice){
                    this._showAlert("El costo no puede ser mayor al precio de venta", "warning")
                    return
                }
                const someMinIsGreaterThanMax = this.form.presentations.some(p => parseFloat(p.min) > parseFloat(p.max))
                const someIsEmpty = (key) => this.form.presentations.some(p => !p[key])
                const amountsIsEmpty = someIsEmpty('amount')
                const presentationsIsEmpty = someIsEmpty('presentation')
                const pricesIsEmpty = someIsEmpty('price')
                const minIsEmpty = someIsEmpty('min')
                const maxIsEmpty = someIsEmpty('max')
                const costIsEmpty = someIsEmpty('cost')
                const umbralIsEmpty = someIsEmpty('umbral')

                if(amountsIsEmpty) {
                    this._showAlert("Debes llenar todas las cantidades de las presentaciones", "warning")
                    return
                }

                if(presentationsIsEmpty) {
                    this._showAlert("Debes llenar todas las presentaciones", "warning")
                    return
                }

                if(pricesIsEmpty) {
                    this._showAlert("Debes llenar todos los precios presentaciones", "warning")
                    return
                }

                 if(minIsEmpty) {
                    this._showAlert("Debes llenar todas las cantidades mínimas de las presentaciones", "warning")
                    return
                }

                if(maxIsEmpty) {
                    this._showAlert("Debes llenar todas las cantidades máximas de las presentaciones", "warning")
                    return
                }

                if(costIsEmpty) {
                    this._showAlert("Debes llenar todos los costos de las presentaciones", "warning")
                    return
                }

                if(umbralIsEmpty) {
                    this._showAlert("Debes llenar todas los umbrales de las presentaciones", "warning")
                    return
                }

                if(someMinIsGreaterThanMax) {
                    this._showAlert("El mínimo de compra no debe ser mayor al máximo en las presentaciones", "warning")
                    return
                }

            }else{
                if(parseFloat(this.form.cost) >= parseFloat(this.form.price_1)){
                    this._showAlert("El costo no puede ser mayor al precio de venta", "warning")
                    return
                }
            }
            if(this.form.variable != 1 && parseFloat(this.form.min) > parseFloat(this.form.max)) {
                this._showAlert("El mínimo de compra no debe ser mayor al máximo", "warning")
                return
            }

            if (this.form.wholesale == 0 && this.form.retail == 0) {
                this._showAlert("Debes seleccionar al menos un tipo de venta", "warning")
                return false;
            }

            // if(this.form.variable == 1) {
            //     this._showAlert("Debes completar los campos referentes a las presentaciones del producto", "warning")
            // }

            // return
            // if (this.form.colors.length == 0) {
            //     this._showAlert("Debes cargar al menos color y existencia del mismo", "warning")
            //     return false;
            // }           
            // for (let x = 0; x < this.form.colors.length; x++) {
            //     if (this.form.colors[x].name == "" || this.form.colors[x].name_english == "") {
            //         this._showAlert("Debes completar los campos referentes a los colores del producto", "warning")
            //         return false
            //     }                
            //     for (let y = 0; y < this.form.colors[x].sizes.length; y++) {
            //         if (typeof this.form.colors[x].sizes[y].amount == 'undefined') {
            //             this._showAlert("Debes completar los campos referentes a los colores del producto", "warning")
            //             return false
            //         }               
            //     }                
            // }

            button.setAttribute('disabled', true);
            // showLoading()
            let percent = document.querySelector('#percent')
            console.log(this._convertToFormData())

            axios.post('admin/products', this._convertToFormData(),  {
                onUploadProgress: function( progressEvent ) {
                    let p = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ))
                    if(p < 100) {
                        percent.innerHTML = `${p}%`
                    }
                }.bind(this)
            })
                .then(resp => {                
                    if (resp.data.result) {
                        swal({
                            title: '',
                            text: 'Producto registrado exitosamente',
                            timer: 2000,
                            showConfirmButton: false,
                            type: "success"
                        }, () => {
                            window.location.reload();
                        })
                    }
                })
                .catch(err => {
                    quiLoading()
                    if(err.response.status === 422){
                        this._showAlert(err.response.data.error, 'warning')
                        return false;
                    }
                    this._showAlert("Disculpa, ha ocurrido un error", "error")
                })
                .then(all => {
                    quiLoading()
                    button.removeAttribute('disabled')
                })
        },

        _convertToFormData(){
            let formData = new FormData();
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
                    if (Array.isArray(this.form[key])) {
                        formData.append(key, JSON.stringify(this.form[key]));
                    }
                    else {
                        formData.append(key, this.form[key]);
                    }                    
                }
            });

            return formData;
        },
    },

    mounted () {
        // console.log('this',)
        this.tabs = M.Tabs.init(document.querySelector(".tabs"))
        this.ids = this.form.images.length
    }
}
</script>

