<template id="template-us-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Nosotros</h1>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <card-main>
                    <card-content>                    
                        <div class="row">
                            <div class="col s12 m6 l6 center-align">
                                <label for="texto" class="label-impegno">¿Quiénes somos? (Español)</label>
                                <textarea name="texto" id="texto" v-model="form.texto" class="browser-default input-impegno"></textarea>
                            </div>

                            <!-- <div class="col s12 m6 l6 center-align">
                                <label for="english" class="label-impegno">¿Quiénes somos? (Inglés)</label>
                                <textarea name="english" id="english" v-model="form.english" class="browser-default input-impegno"></textarea>
                            </div> -->

                            <div class="col s12 m6 l6 center-align">
                                <label for="mision" class="label-impegno">Misión (Español)</label>
                                <textarea name="mision" id="mision" v-model="form.mision" class="browser-default input-impegno"></textarea>
                            </div>

                            <!-- <div class="col s12 m6 l6 center-align">
                                <label for="mision_english" class="label-impegno">Misión (Inglés)</label>
                                <textarea name="mision_english" id="mision_english" v-model="form.mision_english" class="browser-default input-impegno"></textarea>
                            </div> -->

                            <div class="col s12 m6 l6 center-align">
                                <label for="vision" class="label-impegno">Visión (Español)</label>
                                <textarea name="vision" id="vision" v-model="form.vision" class="browser-default input-impegno"></textarea>
                            </div>

                            <!-- <div class="col s12 m6 l6 center-align">
                                <label for="vision_english" class="label-impegno">Visión (Inglés)</label>
                                <textarea name="vision_english" id="vision_english" v-model="form.vision_english" class="browser-default input-impegno"></textarea>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col s12 center-align">
                                <a href="#!" class="btn btn-success" @click="_store($event)" v-if="action == 0">Guardar</a>
                                <a href="#!" class="btn btn-success" @click="_edit($event)" v-else>Actualizar</a>
                            </div>                    
                        </div>                    
                    </card-content>
                </card-main>
            </div>          
        </div>
    </section>
</template>


<style lang="scss">
    textarea {
        resize: none;
        height: 10rem !important;
    }
</style>

<script>
export default {
    template: "#template-us-index",

    props: {
        info: {
            type: Object,
            default: {}
        }
    },

    created () {
        if (Object.keys(this.info).length > 0) {
            this._setAttributes(this.info)
            this.action = 1      
        } else {
            this.action = 0
        }
    },

    data () {
        return {
            action: 0,
            form: {
                id: 0,
                texto: "",
                mision: "",
                vision: "",
                english: "",
                mision_english: "",
                vision_english: "",
            }
        }
    },

    methods: {
        _setAttributes (data) {
            Object.getOwnPropertyNames(data).forEach((key, i) => {
                this.form[key] = data[key] != null ? data[key] : ""
            })                     
        },

        _store(e) {
            let btn = e.target
            btn.setAttribute('disabled', true)
            axios.post('admin/us', this.form).then(res => {
                swal({
                        title: '',
                        text: 'Información almacenada exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location.reload()
                    })
            })
            .catch(err => {
                if (err.response.status == 422) {
                    return swal({
                        title: '',
                        text: err.response.data.error,
                        timer: 3500,
                        showConfirmButton: false,
                        type: "warning"
                    })
                }
                swal({
                    title: 'Disculpa',
                    text: "Ha ocurrido un error",
                    timer: 3500,
                    showConfirmButton: false,
                    type: "error"
                })                
            })
            .then(all => {
                btn.removeAttribute('disabled')
            })
        },

        _edit(e) {
            let btn = e.target
            btn.setAttribute('disabled', true)
            axios.put(`admin/us/${this.form.id}`, this.form).then(res => {
                swal({
                        title: '',
                        text: 'Información actualizada exitosamente',
                        timer: 2000,
                        showConfirmButton: false,
                        type: "success"
                    }, () => {
                        window.location.reload()
                    })
            })
            .catch(err => {
                if (err.response.status == 422) {
                    return swal({
                        title: '',
                        text: err.response.data.error,
                        timer: 3500,
                        showConfirmButton: false,
                        type: "warning"
                    })
                }
                swal({
                    title: 'Disculpa',
                    text: "Ha ocurrido un error",
                    timer: 3500,
                    showConfirmButton: false,
                    type: "error"
                })
            })
            .then(all => {
                btn.removeAttribute('disabled')
            })
        }
    }
}
</script>
