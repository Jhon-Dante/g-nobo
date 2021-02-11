<template>
    <section class="row">
        <div class="col s12 center-align">
            <h1>Perfil</h1>
        </div>
        <div class="col s12">
            <card-main> 
                <card-content>
                    <div class="row">
                        <div class="col s6 input-field">
                            <input type="text" v-model="form.email">
                            <label for="">Email</label>
                        </div>

                        <div class="col s12 input-field">
                            <input type="password" v-model="form.password">
                            <label for="">Cambiar contraseña</label>
                            <span class="helper-text err" v-show="errors.password">{{ errors.password }}</span>
                        </div>

                        <div class="col s12 input-field">
                            <input type="password" v-model="form.password_confirmation">
                            <label for="">Repetir contraseña</label>
                            <span class="helper-text err" v-show="errors.password_confirmation" data-error="wrong" data-success="right">{{ errors.password_confirmation }}</span>
                        </div>

                        <div class="col s12 center-align">
                            <button @click="store" class="btn btn-success" :disabled="passwordError">Actualizar perfil</button>
                        </div>
                    </div>
                </card-content>
            </card-main>
        </div>
    </section>
</template>

<style>
    .err {
        color: #F44336 !important;
    }
</style>


<script>
export default {
    data () {
        return {
            form: {
                // first_name: '',
                // second_name: '',
                // surname: '',
                // middle_name: '',
                email: '',
                // phone: '',
                password: '',
                password_confirmation: ''
            },
            errors: {
                password: '',
                password_confirmation: ''
            }
        }
    },
    props: {
        user: {
            type: Object,
            default () {
                return {}
            }
        }
    },
    watch: {
        form: {
            handler (form) {
                if(form.password && !form.password_confirmation) {
                    this.errors.password_confirmation = 'Debe confirmar la contraseña nueva'
                    return
                }else {
                    this.errors.password_confirmation = ''
                }

                if(form.password || form.password_confirmation) { // Si alguno de los dos tiene datos
                    if(form.password != form.password_confirmation) {
                        this.errors.password = 'Las contarseñas no coinciden'
                    }else {
                        this.errors.password = ''
                    }
                }
            },
            deep: true
        }
    },
    computed: {
        passwordError () {
            let changingPassword = this.form.password != '' || this.form.password_confirmation != ''
            let errorPassword = this.errors.password != '' || this.errors.password_confirmation !=''

            return changingPassword && errorPassword
        },
    },
    methods: {
        store () {
            axios.post('admin/profile', this.form)
            .then(res => {
                swal('', 'Se actualizó el perfil correctamente')
            })
            .catch(err => {
                if(err.response.status === 422){
                        swal({
                        title: "error",
                        text: err.response.data.email[0],
                        timer: 2000,
                        showConfirmButton: false,
                        type: "error"
                    });
                    return
                }

                swal({
                    title: "",
                    text: `Algo ha ocurrido`,
                    timer: 2000,
                    showConfirmButton: false,
                    type: "error"
                });
                
            })
        }
    },
    mounted () {
        // this.form.first_name = this.user.person.first_name
        // this.form.surname = this.user.person.surname

        // if(this.user.level == 2 || this.user.level == 1) {
        //     this.form.middle_name = this.user.person.middle_name
        //     this.form.second_name = this.user.person.second_name
        // }

        // this.form.phone = this.user.person.phone
        this.form.email = this.user.email;
    }
}
</script>