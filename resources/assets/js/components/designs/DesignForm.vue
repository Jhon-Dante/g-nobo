<template id="template-design-form">
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
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6 center-align">
                    <label for="name" class="label-impegno">Diseño (Español)</label>
                    <input type="text" name="name" id="name" v-model="form.name" maxlength="50" class="browser-default input-impegno">
                </div>

                <div class="col s12 m6 l6 center-align">
                    <label for="name_english" class="label-impegno">Diseño (Inglés)</label>
                    <input type="text" name="name_english" id="name_english" v-model="form.name_english" maxlength="50" class="browser-default input-impegno">
                </div>
                <div class="col s12 m12 l12 margin-top center-align">
                    <a href="#!" class="btn btn-success" @click="_store($event)" v-if="action == 0">Guardar</a>
                    <a href="#!" class="btn btn-success" @click="_edit($event)" v-else>Actualizar</a>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .margin-top{
        margin-top: 20px;
    }
</style>

<script>
export default {
    template: "#template-design-form",

    props: {
        action: {
            type: Number,
            default: 0
        },

        data: {
            type: Object,
            default () {
                return {}
            }
        }
    },

    created() {
       this.form = this.action != 0 ? this.data : {id: 0, name: "", name_english: ""}
    },

    data () {
        return {
            form: {
                id: 0,
                name: "",
                name_english: ""
            }
        }
    },

    methods: {
        _back() {
            this.$emit('back', 0)
        },

        _store (e) {
            e.preventDefault()
            e.target.setAttribute('disabled', true)
            axios.post('admin/designs', this.form)
            .then(res => {
                this._showAlert(res.data.message, "success")
                setTimeout(() => {
                    this.$emit('back', 0)
                    this.$emit('reload')
                }, 3001);
            })
            .catch(err => {
                let message = "Disculpe, ha ocurrido un error";
                if(err.response.status === 422){                        
                    message = err.response.data.error;
                }
                this._showAlert(message, "error")
            })
            .then(all => {
                e.target.removeAttribute('disabled')
            })
        },

        _edit (e) {
            e.preventDefault()
            e.target.setAttribute('disabled', true)
            axios.put(`admin/designs/${this.form.id}`, this.form)
            .then(res => {
                this._showAlert(res.data.message, "success")
                setTimeout(() => {
                    this.$emit('back', 0)
                    this.$emit('reload')
                }, 3001);
            })
            .catch(err => {
                let message = "Disculpe, ha ocurrido un error";
                if(err.response.status === 422){                        
                    message = err.response.data.error;
                }
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
        },
    }
}
</script>
