<template id="template-login-form">
    <article class="row">
        <div class="col s12">

            <a id="form-logo-container" href="#!" class="form-brand-logo">
                <img :src="'img/logo-intro.png' | asset" alt="" width="380px">
            </a>

            <card-main id="action-form">
                <card-content>
                    <card-title>
                        <h1 class="no-margin">¡Bienvenido!</h1>
                    </card-title>

                    <form id="login-form" @submit.prevent="login">
                        <div class="input-field">
                            <!-- <i class="material-icons prefix">mail</i> -->
                            <!-- <img :src="'img/icons/ico-email.png' | asset"> -->
                            <input id="email" type="text" class="browser-default input-impegno" placeholder="Correo Electrónico" v-model="form.email">
                        </div>

                        <div class="input-field">
                            <!-- <img :src="'img/icons/ico-contrasena.png' | asset"> -->
                            <input id="password" type="password" class="browser-default input-impegno" placeholder="Contraseña" v-model="form.password">
                        </div>

                        <div class="center-align">
                            <button class="btn btn-continue" type="submit" name="action">Iniciar Sesión</button>
                        </div>
                    </form>
                </card-content>
            </card-main>
        </div>
    </article>
    
</template>

<script>
export default {
    template: "#template-login-form",
    props: {
        url: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            form: {
                email: "",
                password: ""
            }
        }
    },
    methods:{
        login(){
            axios.post('admin/login', this.form)
                .then(res => {
                    if(res.data.result){
                        window.location = res.data.location;
                    }else{
                        this._showAlert(res.data.error, "error")
                    }
                })
                .catch(err => {
                    let message = "Disculpe, ha ocurrido un error";

                    if(err.response.status === 422){                        
                        message = err.response.data.error;
                    }
                    this._showAlert(message, "error")
                });
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
