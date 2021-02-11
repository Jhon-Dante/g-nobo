var vue_modal = new Vue({
    el: '#loginModal',
    data: {
        form: {
            email: '',
            password: ''
        },
        formRegister: {
            name: '',
            type: 'natural',
            identificacion: '',
            empresa: '',
            fiscal: '',
            email: '',
            estado: '',
            municipio: '',
            parroquia: '',
            direccion: '',
            referencia: '',
            password: '',
            password_confirmation: '',
        },
        formRecovery: {
            email: '',
            codigo: '',
            password: '',
            password_confirmation: ''
        },
        terminos: true,
        option: 'login',
        optionRecovery: '1'
    },
    methods: {
        setOptionDefault: function() {
            const vueLocal = this
            vueLocal.option = 'login';
        },
        submit: function() {
            setLoader();
            const cart = localStorage.getItem('cart') && JSON.parse(localStorage.getItem('cart')) || []
            const count = cart.length;
            const loginForm = {
                ...vue_modal.form,
                count
            }
            axios.post(loginUrl, loginForm)
                .then(function(res) {
                    if (res.data.result) {
                        window.location = res.data.url;
                    }
                    else {
                        swal('',res.data.error,'warning');
                    }
                })
                .catch(function(err) {
                    console.log('>>: err > ', err )
                    window.location.reload()
                    swal('', pageError,'warning');
                })
                .then(function() {
                    quitLoader();
                });
        },
        signin: function() {
            if (!vue_modal.terminos) {
                swal('',messageTerminos,'warning');
                return false;
            }
            setLoader();
            axios.post(registerUrl,vue_modal.formRegister)
                .then(function(res) {
                    if (res.data.result) {
                        swal('',loginSuccess,'success');
                        setTimeout(function() {
                            window.location = res.data.url;
                        },1500);
                    }
                    else {
                        swal('',res.data.error,'warning');
                    }
                })
                .catch(function(err) {
                    swal('',registerError,'warning');
                })
                .then(function() {
                    quitLoader();
                });
        },
        sendEmailRecovery: function() {
            const vueLocal = this
            setLoader()
            axios.post('recuperar/send',{email: this.formRecovery.email})
            .then(function(res) {
                if (res.data.result) {
                        swal('','Correo enviado exitosamente!','success');
                        vueLocal.optionRecovery = '2';
                    }
                    else {
                        swal('',res.data.error,'warning');
                    }
                })
                .catch(function(err) {
                    console.log(err, 'err')
                    swal('','Se ha producido un error','warning');
                })
                .then(function() {
                    quitLoader();
                });
        },
        sendCodeRecovery: function() {
            const vueLocal = this
            setLoader()
            axios.post('recuperar/codigo',{codigo: this.formRecovery.codigo})
                .then(function(res) {
                    if (res.data.result) {
                        swal('','Codigo validado exitosamente!','success');
                        vueLocal.optionRecovery = '3'
                    }
                    else {
                        swal('',res.data.error,'warning');
                    }
                })
                .catch(function(err) {
                    console.log(err, 'err')
                    swal('','Se ha producido un error','warning');
                })
                .then(function() {
                    quitLoader();
                });
        },
        sendPasswordRecovery: function() {
            setLoader()
            const vueLocal = this
            axios.post('recuperar/recuperar', this.formRecovery)
                .then(function(res) {
                    if (res.data.result) {
                        swal('','Contraseña modificada exitosamente!','success');
                        location.reload();
                        vueLocal.formRecovery = {
                            email: '',
                            codigo: '',
                            password: '',
                            password_confirmation: ''
                        }
                        vueLocal.optionRecovery = '1';
                        vueLocal.option = 'login';
                    }
                    else {
                        swal('',res.data.error,'warning');
                    }
                })
                .catch(function(err) {
                    console.log(err, 'err')
                    swal('','Se ha producido un error','warning');
                })
                .then(function() {
                    quitLoader();
                });
        },
        replaceStep: function (step) {
            const vueLocal = this;
            vueLocal.optionRecovery = step;
        }
    }
})