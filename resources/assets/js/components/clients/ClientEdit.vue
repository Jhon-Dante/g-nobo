<template>
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
                <div id="test1" class="col s12">
                    <div class="row container-form">
                        <div class="col s12 m6 l6 center-align">
                            <label for="name" class="label-impegno">Nombre</label>
                            <input type="text" name="name" id="name" v-model="form.name" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="name" class="label-impegno">Correo Electronico</label>
                            <input type="email" name="email" id="email" v-model="form.email" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="type" class="label-impegno">Tipo de Personalidad</label>
                            <select name="type" id="type" class="browser-default" v-model="form.type">
                                <option value="">Seleccione</option>
                                <option value="1">Persona Natural</option>
                                <option value="2">Persona Jurídica</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="identificacion" class="label-impegno">C.I./RIF</label>
                            <input type="number" name="identificacion" id="identificacion" v-model="form.identificacion" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="estado_id" class="label-impegno">Estado</label>
                            <select name="estado_id" id="estado_id" class="browser-default" @change="_setMunicipalities(form.estado_id); form.municipality_id = ''; form.parish_id = ''" v-model="form.estado_id">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in states">{{ item.nombre }}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="municipality_id" class="label-impegno">Municipio</label>
                            <select name="municipality_id" id="municipality_id" class="browser-default" @change="_setParishes(form.municipality_id); form.parish_id = ''" v-model="form.municipality_id">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in municipalities">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="parish_id" class="label-impegno">Sector</label>
                            <select name="parish_id" id="parish_id" class="browser-default" v-model="form.parish_id">
                                <option value="">Seleccione</option>
                                <option :value="item.id" :key="index" v-for="(item, index) in parishes">{{ item.name }}</option>
                            </select>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="telefono" class="label-impegno">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" v-model="form.telefono" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div v-if="form.type == '2'" class="col s12 m6 l6 center-align">
                            <label for="empresa" class="label-impegno">Razón social</label>
                            <input type="text" name="empresa" id="empresa" v-model="form.empresa" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div v-if="form.type == '2'" class="col s12 m6 l6 center-align">
                            <label for="fiscal" class="label-impegno">Dirección fiscal</label>
                            <input type="text" name="fiscal" id="fiscal" v-model="form.fiscal" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="password" class="label-impegno">Contraseña</label>
                            <input type="password" name="password" id="password" v-model="form.password" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="password_confirmation" class="label-impegno">Repetir Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" v-model="form.password_confirmation" maxlength="50" class="browser-default input-impegno">
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="direccion" class="label-impegno">Dirección</label>
                            <textarea name="direccion" id="direccion" v-model="form.direccion" class="browser-default textarea-impegno"></textarea>
                        </div>
                        <div class="col s12 m6 l6 center-align">
                            <label for="referencia" class="label-impegno">Punto de Referencia</label>
                            <textarea name="referencia" id="referencia" v-model="form.referencia" class="browser-default textarea-impegno"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12 margin-top center-align">
                <a href="#!" class="btn btn-success" :disabled="sending" @click="_edit($event)">Actualizar</a>
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
     .progress {
        opacity: 0;
        transition: all ease-in-out 0.35s;
    }
    .progress-active {
        opacity: 1;
    }
</style>

<script>

export default {
    props: {
        states: {
            type: Array,
            default: []
        },
        data: {
            type: Object,
            default: {}
        }
    },

    data () {
        return {
            uploadPercentage: 0,
            sending: false,
            form: {},
            selectedMunicipality: {},
            municipalities: [],
            parishes: []
        }
    },

    async mounted() {
        const data = {...this.data}
        this.form = data;
        console.log('fomr', this.form)
        await this._setMunicipalities(this.form.estado_id)
        this._setParishes(this.form.municipality_id)
    },

    methods: {
        _back() {
            this.$emit('back', 0)
        },

        async _setMunicipalities(id) {
            showLoading()
            const { data } = await axios.get(`admin/municipalities/get-by-state/${id}`);
            this.municipalities = data;
            quiLoading()
        },

        _setParishes(id) {            
            if (id == "" && id == 0) {
                this.parishes = []
            }else {
                this.selectedMunicipality = this.municipalities.find((el) => {
                    return (el.id == id)
                })
                this.parishes = this.selectedMunicipality.parishes
            }  
        },

        _edit (e) {
            let button = e.target

            if (this.form.wholesale == 0) {
                this._showAlert("Debes seleccionar al menos un tipo de venta", "warning")
                return false;
            }

            button.setAttribute('disabled', true)
            showLoading()
            axios.post(`admin/clients/update`, this._convertToFormData())
                .then(resp => {                
                    if (resp.data.result) {
                        swal({
                            title: '',
                            text: 'Cliente editado exitosamente',
                            timer: 2000,
                            showConfirmButton: false,
                            type: "success"
                        }, () => {
                            location.reload();
                        })
                    }
                })
                .catch(err => {
                    if(err.response.status === 422){
                        this._showAlert(err.response.data.error, 'warning')
                        return false;
                    }
                    
                    this._showAlert("Disculpa, ha ocurrido un error", "error")
                })
                .then(all => {
                    button.removeAttribute('disabled')
                    quiLoading()
                })
        },


        _convertToFormData(){
            let formData = new FormData();
            formData.append('_method', 'POST');
            Object.getOwnPropertyNames(this.form).forEach((key, i) => {
                if(key != "__ob__"){
                    formData.append(key, this.form[key]);                   
                }
            });

            return formData;
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
    },
}
</script>
