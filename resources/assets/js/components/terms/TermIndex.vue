<template id="template-us-index">
    <section class="container-fluid">
        <div class="row">
            <div class="col s12 center-align">
                <h1>Terminos y condiciones de compra</h1>
            </div>
        </div>
        <div class="row">
            <card-main>
                <card-content>                    
                    <div class="row">
                        <div class="col s12 m6 l6 center-align">
                            <label for="texto" class="label-impegno">Terminos y condiciones (Español)</label>
                            <textarea name="texto" id="texto" v-model="form.terms_text" class="browser-default input-impegno"></textarea>
                        </div>

                       <!-- <div class="col s12 m6 l6 center-align">
                            <label for="english" class="label-impegno">Terminos y condiciones (Inglés)</label>
                            <textarea name="english" id="english" v-model="form.terms_english" class="browser-default input-impegno"></textarea>
                        </div> -->

                        <div class="col s12 m6 l6 center-align">
                            <label for="mision" class="label-impegno">Condiciones de compra (Español)</label>
                            <textarea name="mision" id="mision" v-model="form.conditions_text" class="browser-default input-impegno"></textarea>
                        </div>

                        <!-- <div class="col s12 m6 l6 center-align">
                            <label for="mision_english" class="label-impegno">Condiciones de compra (Inglés)</label>
                            <textarea name="mision_english" id="mision_english" v-model="form.conditions_english" class="browser-default input-impegno"></textarea>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col s12 center-align">
                            <a href="#!" class="btn btn-success" @click="_store($event)" v-if="action == 0" :disabled="sending">Guardar</a>
                        </div>                    
                    </div>                    
                </card-content>
            </card-main>
        </div>          
    </section>
</template>


<style lang="scss">
    textarea {
        resize: none;
        height: 25rem !important;
    }
</style>

<script>
export default {
    template: "#template-us-index",

    props: {
        terms: {
            type: Object,
            default: {}
        },
        conditions: {
            type: Object,
            default: {}
        }
    },

    data () {
        return {
            action: 0,
            form: {
                terms_text: '',
                terms_english: '',
                conditions_text: '',
                conditions_english: ''
            },
            sending: false
        }
    },

    methods: {
        _store(e) {
            this.sending = true
            axios.post('admin/terms', this.form).then(res => {
                this.sending = false
                swal('', 'Información almacenada exitosamente', 'success')
            })
            .catch(err => {
                this.sending = false
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
        },

    },
    mounted () {
        this.form.terms_text = this.terms.texto
        this.form.terms_english = this.terms.english

        this.form.conditions_text = this.conditions.texto
        this.form.conditions_english = this.conditions.english
    }
}
</script>
