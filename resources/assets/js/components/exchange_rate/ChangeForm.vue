<template id="template-change-form">
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
                <div class="col s12 m6 l6  offset-l3  offset-m3 center-align">
                    <label for="change" class="label-impegno">Tasa de cambio</label>
                    <input type="number" name="change" id="name_english" v-model="form.change" step="any" class="browser-default input-impegno">
                </div>
                <div class="col s12 m12 l12 margin-top center-align">
                    <a href="#!" class="btn btn-success" @click="_store($event)" >Guardar</a>
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
    template: "#template-change-form",

    props: {
    },

    data () {
        return {
            form: {
                id: 0,
                change: ""
            }
        }
    },

    methods: {
        _back() {
            this.$emit('back', 0)
        },

        _store (e) {
            e.preventDefault()
            if (this.form.change == "") {
                this._showAlert("Disculpa, debes cargar la tasa de cambio", "success")
                return false
            }
            e.target.setAttribute('disabled', true)
            axios.post('admin/exchange_rate', this.form)
                .then(res => {
                    this._showAlert(res.data.message, "success")
                    setTimeout(() => {
                        location.reload();
                    }, 3001);
                    
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";
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
